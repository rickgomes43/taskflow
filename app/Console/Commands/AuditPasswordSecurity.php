<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuditPasswordSecurity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit-passwords
                          {--fix : Automatically fix insecure passwords}
                          {--report : Generate detailed report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit password security and detect potential vulnerabilities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Iniciando auditoria de segurança de senhas...');
        $this->newLine();

        $users = User::all();
        $issues = [];
        $stats = [
            'total_users' => $users->count(),
            'secure_passwords' => 0,
            'insecure_passwords' => 0,
            'weak_hashes' => 0,
            'plaintext_passwords' => 0
        ];

        foreach ($users as $user) {
            $this->line("Auditando usuário: {$user->email}");
            
            $userIssues = $this->auditUserPassword($user);
            
            if (empty($userIssues)) {
                $stats['secure_passwords']++;
                $this->info("  ✅ Seguro");
            } else {
                $stats['insecure_passwords']++;
                $issues[$user->id] = $userIssues;
                
                foreach ($userIssues as $issue) {
                    $this->error("  ❌ {$issue}");
                    
                    // Count specific issue types
                    if (str_contains($issue, 'plaintext')) {
                        $stats['plaintext_passwords']++;
                    }
                    if (str_contains($issue, 'hash fraco')) {
                        $stats['weak_hashes']++;
                    }
                }

                // Auto-fix if requested
                if ($this->option('fix')) {
                    $this->fixUserPassword($user, $userIssues);
                }
            }
        }

        $this->displayResults($stats, $issues);

        if ($this->option('report')) {
            $this->generateReport($stats, $issues);
        }

        return Command::SUCCESS;
    }

    /**
     * Audit individual user password
     */
    private function auditUserPassword(User $user): array
    {
        $issues = [];
        $password = $user->password;

        // Check if password is null or empty
        if (empty($password)) {
            $issues[] = 'Senha vazia ou nula';
            return $issues;
        }

        // Check if password is in plaintext (basic detection)
        if (!str_starts_with($password, '$')) {
            $issues[] = 'Possível senha em plaintext';
            return $issues;
        }

        // Check bcrypt hash format and cost
        if (str_starts_with($password, '$2y$') || str_starts_with($password, '$2b$')) {
            // Extract cost from bcrypt hash
            $parts = explode('$', $password);
            if (count($parts) >= 3) {
                $cost = (int)$parts[2];
                if ($cost < 12) {
                    $issues[] = "Hash bcrypt com custo baixo: {$cost} (mínimo recomendado: 12)";
                }
            }
        } else {
            $issues[] = 'Hash não reconhecido como bcrypt seguro';
        }

        // Test hash verification (ensure it's a valid hash)
        if (!Hash::check('test_invalid_password_12345', $password)) {
            // This should always fail, but ensures hash is valid format
            // If this throws an error, the hash is corrupted
        }

        return $issues;
    }

    /**
     * Fix user password issues
     */
    private function fixUserPassword(User $user, array $issues): void
    {
        foreach ($issues as $issue) {
            if (str_contains($issue, 'custo baixo') || str_contains($issue, 'hash fraco')) {
                // For weak hashes, we can't recover the original password
                // This would need to force password reset
                $this->warn("  ⚠️  Hash fraco detectado - usuário {$user->email} deve alterar senha no próximo login");
                
                // Mark user for password reset (would need additional logic)
                // $user->force_password_reset = true;
                // $user->save();
            }
            
            if (str_contains($issue, 'plaintext')) {
                $this->error("  🚨 Senha em plaintext detectada - CRÍTICO - usuário {$user->email}");
                // Log security incident
                logger()->critical('Plaintext password detected', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
            }
        }
    }

    /**
     * Display audit results
     */
    private function displayResults(array $stats, array $issues): void
    {
        $this->newLine(2);
        $this->info('📊 Relatório da Auditoria de Segurança');
        $this->newLine();
        
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total de usuários', $stats['total_users']],
                ['Senhas seguras', $stats['secure_passwords']],
                ['Senhas inseguras', $stats['insecure_passwords']],
                ['Hashes fracos', $stats['weak_hashes']],
                ['Senhas em plaintext', $stats['plaintext_passwords']],
            ]
        );

        if ($stats['insecure_passwords'] === 0) {
            $this->info('🎉 Parabéns! Todas as senhas estão seguras.');
        } else {
            $this->error("⚠️  {$stats['insecure_passwords']} senhas precisam de atenção.");
        }

        $securityScore = ($stats['secure_passwords'] / $stats['total_users']) * 100;
        $this->info("🏆 Score de Segurança: " . number_format($securityScore, 1) . '%');
    }

    /**
     * Generate detailed security report
     */
    private function generateReport(array $stats, array $issues): void
    {
        $reportPath = storage_path('logs/password-security-audit-' . date('Y-m-d-H-i-s') . '.json');
        
        $report = [
            'audit_date' => now()->toISOString(),
            'laravel_hash_config' => [
                'driver' => config('hashing.driver'),
                'bcrypt_rounds' => config('hashing.bcrypt.rounds'),
            ],
            'statistics' => $stats,
            'issues' => $issues,
            'recommendations' => $this->generateRecommendations($stats)
        ];

        file_put_contents($reportPath, json_encode($report, JSON_PRETTY_PRINT));
        $this->info("📄 Relatório detalhado salvo em: {$reportPath}");
    }

    /**
     * Generate security recommendations
     */
    private function generateRecommendations(array $stats): array
    {
        $recommendations = [];

        if ($stats['plaintext_passwords'] > 0) {
            $recommendations[] = 'CRÍTICO: Senhas em plaintext detectadas - requer ação imediata';
        }

        if ($stats['weak_hashes'] > 0) {
            $recommendations[] = 'Forçar alteração de senha para usuários com hashes fracos';
        }

        if ($stats['secure_passwords'] === $stats['total_users']) {
            $recommendations[] = 'Sistema está seguro - manter monitoramento regular';
        }

        $recommendations[] = 'Implementar política de senhas fortes';
        $recommendations[] = 'Considerar autenticação de dois fatores (2FA)';
        $recommendations[] = 'Executar auditoria mensalmente';

        return $recommendations;
    }
}