<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Painel de Administração</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-900">Gerenciar Usuários</h4>
                            <p class="text-sm text-blue-700 mt-2">Administre contas de usuário, papéis e permissões.</p>
                            <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                Ver usuários →
                            </a>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-900">Relatórios</h4>
                            <p class="text-sm text-green-700 mt-2">Visualize estatísticas e relatórios do sistema.</p>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-900">Configurações</h4>
                            <p class="text-sm text-purple-700 mt-2">Ajuste configurações gerais do sistema.</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="font-semibold mb-2">Informações do Usuário</h4>
                        <p class="text-sm text-gray-600">
                            Logado como: <span class="font-semibold">{{ auth()->user()->name }}</span> 
                            ({{ auth()->user()->role }})
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>