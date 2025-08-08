# Documento de Segurança de Senhas - TaskFlow

## Visão Geral

Este documento descreve as práticas de segurança implementadas no TaskFlow para proteger senhas e autenticação dos usuários. O sistema segue as melhores práticas de segurança da indústria.

## Configurações de Hashing

### Driver de Hash
- **Driver:** bcrypt (padrão)
- **Rounds/Cost:** 12 (produção) / 4 (testes)
- **Rehash on Login:** Habilitado
- **Algoritmo:** bcrypt com salt automático

### Por que bcrypt?
- Algoritmo adaptativo - custo pode ser aumentado conforme hardware melhora
- Salt automático único por senha
- Resistente a ataques de rainbow table
- Implementação madura e testada

## Implementações de Segurança

### 1. Hashing de Senhas
```php
// Todas as senhas são hashadas com Hash::make()
$user->password = Hash::make($request->password);

// Verificação sempre usa Hash::check()
Hash::check($password, $user->password);
```

### 2. Validação de Força de Senha
```php
'password' => [
    'required',
    'string',
    'min:8',
    'confirmed',
    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
],
```

**Critérios:**
- Mínimo 8 caracteres
- Pelo menos 1 letra minúscula
- Pelo menos 1 letra maiúscula
- Pelo menos 1 número
- Confirmação obrigatória

### 3. Rate Limiting
- **Registro:** 5 tentativas por minuto por IP
- **Login:** Rate limiting implementado via middleware throttle
- **Proteção CSRF:** Habilitada em todos os formulários

### 4. Logs de Segurança
O sistema registra automaticamente:
- Tentativas de login (sucessos e falhas)
- Tentativas de registro
- Eventos de logout
- Rate limiting violations
- User agents suspeitos
- Tentativas de brute force

**Canal de Log:** `security` (storage/logs/security-YYYY-MM-DD.log)
**Retenção:** 90 dias

### 5. Auditoria Automática
Comando disponível: `php artisan security:audit-passwords`

**Funcionalidades:**
- Detecção de senhas em plaintext
- Verificação de custos de hash fracos
- Análise de formato de hash
- Relatórios detalhados com recomendações
- Score de segurança geral

## Configurações de Produção

### config/hashing.php
```php
'driver' => 'bcrypt',
'bcrypt' => [
    'rounds' => 12, // Mínimo recomendado
    'verify' => true,
],
'rehash_on_login' => true,
```

### Variáveis de Ambiente
```env
LOG_SECURITY_DAYS=90  # Retenção de logs de segurança
BCRYPT_ROUNDS=12      # Custo bcrypt para produção
```

## Práticas de Desenvolvimento

### 1. Sempre Use Hash Facade
```php
// ✅ CORRETO
$password = Hash::make($plaintext);
$isValid = Hash::check($plaintext, $hash);

// ❌ INCORRETO
$password = bcrypt($plaintext);  // Não usar diretamente
$password = md5($plaintext);     // Nunca usar MD5/SHA1
```

### 2. Nunca Armazene Plaintext
```php
// ❌ NUNCA FAÇA ISSO
$user->password = $request->password;

// ✅ SEMPRE FAÇA ASSIM
$user->password = Hash::make($request->password);
```

### 3. Use Form Requests para Validação
```php
// UserRegistrationRequest.php
public function rules(): array
{
    return [
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ],
    ];
}
```

## Monitoramento e Alertas

### Métricas de Segurança
- Score de segurança geral (% de senhas seguras)
- Taxa de falhas de login por IP
- Tentativas de registro suspeitas
- Violações de rate limiting

### Alertas Críticos
O sistema gera alertas para:
- **CRÍTICO:** Senhas em plaintext detectadas
- **ALTO:** Múltiplas tentativas de brute force
- **MÉDIO:** Hashes com custo baixo
- **INFO:** Atividade normal de autenticação

### Logs Estruturados
```json
{
    "level": "warning",
    "message": "Failed login attempt",
    "context": {
        "email": "user@example.com",
        "ip": "192.168.1.1",
        "user_agent": "Mozilla/5.0...",
        "timestamp": "2025-01-15T10:30:00Z"
    }
}
```

## Testes de Segurança

### Testes Implementados
1. **Configuração de Hash**
   - Verificação do driver bcrypt
   - Validação do custo mínimo
   - Teste de rehash on login

2. **Funcionalidade de Hash**
   - Unicidade de hashes (salt)
   - Verificação correta
   - Performance adequada
   - Formato de hash válido

3. **Validação de Senhas**
   - Força de senha
   - Armazenamento seguro
   - Casting do model

4. **Auditoria**
   - Comando de auditoria funcional
   - Detecção de vulnerabilidades
   - Relatórios corretos

### Executar Testes
```bash
./vendor/bin/sail test tests/Feature/Security/PasswordSecurityTest.php
```

## Procedimentos de Resposta a Incidentes

### Senha Comprometida
1. Forçar reset de senha do usuário
2. Invalidar todas as sessões ativas
3. Revisar logs de atividade suspeita
4. Notificar o usuário sobre o incidente

### Ataque de Brute Force
1. Aumentar rate limiting temporariamente
2. Bloquear IPs suspeitos se necessário
3. Analisar padrões de ataque
4. Considerar implementar CAPTCHA

### Vulnerabilidade Detectada
1. Executar auditoria completa: `php artisan security:audit-passwords --report`
2. Revisar relatório de segurança
3. Implementar correções necessárias
4. Re-executar testes de segurança

## Recomendações Futuras

### Melhorias Planejadas
1. **Autenticação de Dois Fatores (2FA)**
   - TOTP (Google Authenticator)
   - SMS backup
   - Recovery codes

2. **Política de Senhas Avançada**
   - Histórico de senhas (não reutilizar últimas 5)
   - Expiração opcional de senhas
   - Detecção de senhas comuns/vazadas

3. **Monitoramento Avançado**
   - Integração com SIEM
   - Alertas em tempo real
   - Dashboard de segurança

4. **Auditoria Contínua**
   - Execução automática mensal
   - Alertas proativos
   - Relatórios executivos

## Conformidade e Padrões

### Padrões Seguidos
- **OWASP Authentication Cheat Sheet**
- **NIST SP 800-63B** (Digital Identity Guidelines)
- **GDPR** (Proteção de dados pessoais)
- **ISO 27001** (Gestão de segurança da informação)

### Checklist de Conformidade
- ✅ Senhas hashadas com algoritmo seguro (bcrypt)
- ✅ Salt único por senha
- ✅ Custo adequado para tempo de processamento
- ✅ Validação de força de senha
- ✅ Rate limiting implementado
- ✅ Logs de segurança estruturados
- ✅ Auditoria regular automatizada
- ✅ Testes de segurança automatizados

---

**Documento atualizado em:** Janeiro 2025  
**Versão:** 1.0  
**Responsável:** Equipe de Segurança TaskFlow