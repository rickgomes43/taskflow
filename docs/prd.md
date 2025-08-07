## Visão Geral do Produto

O TaskFlow é uma plataforma web de gestão de tarefas e controle de tempo criada especialmente para profissionais autônomos, freelancers e pequenos estúdios que prestam serviços recorrentes ou pontuais a seus clientes — como desenvolvimento de sites, produção de conteúdo, social media, e suporte técnico.

A proposta é centralizar o acompanhamento de entregas, aprovações e uso de horas contratadas em um sistema simples, visual e intuitivo, com fluxos personalizáveis por tipo de projeto. O TaskFlow permite criar modelos de pipeline para diferentes serviços (como produção de blogposts, pacotes de social media, desenvolvimento de landing pages ou pacotes de horas), facilitando a organização do que foi contratado, o que está em produção, o que depende de aprovação e o que já foi entregue.

Cada tarefa pode ter uma visualização própria — por exemplo, um post de blog pode conter o conteúdo pronto para validação e comentários. Já um projeto de site pode seguir um fluxo definido com etapas visuais, como design, conteúdo, desenvolvimento e publicação.

Além disso, o cliente pode acompanhar em tempo real suas demandas, interagir com a equipe, aprovar entregas e acessar relatórios sobre uso de horas e status geral do que está sendo feito. O TaskFlow transforma a comunicação em algo transparente e prático, com foco total em produtividade, clareza e personalização.

---

## Objetivos e Métricas de Sucesso

* Centralizar a comunicação e acompanhamento de projetos em um único sistema, eliminando a necessidade de múltiplas planilhas, mensagens e e-mails.
* Oferecer visibilidade total para o cliente sobre o status das tarefas, aprovações e uso de horas contratadas.
* Aumentar a produtividade por meio da padronização de processos com modelos reutilizáveis de pipelines e tarefas.
* Reduzir o retrabalho com validações claras e centralizadas por tipo de entrega.
* Facilitar a gestão de tempo com play/pause, histórico de atividades e relatórios de desempenho.

### Indicadores de Sucesso:

* Adoção completa do sistema por pelo menos 80% dos clientes ativos em 90 dias
* Redução de 50% no tempo médio de aprovação de tarefas (comparado ao processo atual via e-mail ou WhatsApp)
* Aumento de 30% na taxa de entregas feitas dentro do prazo
* Redução de pelo menos 1 hora por semana em gestão manual de tarefas e comunicação

---

## Funcionalidades Principais

* Autenticação segura (com suporte a login via Google)
* Cadastro e gerenciamento de clientes, empresas e equipes
* Criação e personalização de projetos e pipelines (Kanban)
* Modelos reutilizáveis de tarefas e fluxos por tipo de serviço
* Criação de tarefas com estimativa de horas, prioridade e comentários
* Controle de tempo por tarefa com play/pause (uma tarefa ativa por vez)
* Visualizações por período (hoje, semana, mês) e filtros por status
* Visão personalizada para o cliente: tarefas, aprovações e status
* Aprovação de tarefas com campo de comentário integrado
* Relatórios de horas contratadas vs. horas utilizadas
* Exportação de relatórios em CSV/XLS/PDF
* Notificações por e-mail para aprovações e alterações

---

## Requisitos Não Funcionais

* O sistema deve estar disponível em ambiente web responsivo, com suporte total a dispositivos desktop e mobile.
* A interface deve ser simples, intuitiva e com foco em usabilidade, mesmo para usuários com pouca familiaridade técnica.
* As informações devem ser salvas automaticamente e/ou com confirmação visual clara de envio/atualização.
* O sistema deve garantir autenticação segura, com suporte a login via Google e proteção contra acessos indevidos.
* As ações realizadas pelos usuários (ex: play/pause, aprovações, comentários) devem ser registradas para rastreabilidade.
* As exportações de relatórios devem ocorrer em formatos amplamente compatíveis (CSV, XLS, PDF).
* A arquitetura deve permitir escalabilidade futura para múltiplas empresas, times e projetos.

---

## Restrições e Premissas

### Premissas

* O sistema será utilizado inicialmente por uma única empresa (você), com possibilidade futura de uso por outras empresas.
* Haverá poucos colaboradores internos simultâneos, com foco em centralizar a operação e facilitar a participação pontual de parceiros.
* Os clientes não precisam criar contas manuais — o acesso pode ser feito via convites personalizados ou links privados.
* O uso será gradual, priorizando contratos ativos e clientes com maior volume de entregas.

### Restrições

* O MVP será desenvolvido para web (desktop/mobile), sem apps nativos neste primeiro momento.
* O sistema será operado inicialmente com backend e infraestrutura em nuvem com baixo custo (ex: Laravel + Docker + VPS).
* O gerenciamento de pagamentos, contratos e faturas será feito fora da plataforma por enquanto.
* Integrações com ferramentas externas (ex: Slack, Notion, Trello) não serão prioridade no MVP.

---

## Personas

### 1. Rick (Você) – Profissional autônomo / Gerente de projetos

Responsável por atender múltiplos clientes com diferentes demandas, como criação de sites, conteúdo, redes sociais e suporte. Precisa de organização clara por projeto, visibilidade sobre tarefas em andamento, controle das horas contratadas e facilidade para montar pipelines prontos. Atua também como ponte entre o time e o cliente, realizando entregas e acompanhando aprovações.

### 2. Cliente – Empresário ou responsável pelo marketing

Geralmente possui pouco tempo e pouca familiaridade com ferramentas técnicas. Quer ter visibilidade rápida do que está sendo produzido, aprovar ou solicitar ajustes com facilidade, e entender se os serviços contratados estão sendo executados dentro do prazo e das horas previstas.

### 3. Colaboradores pontuais – Designer, dev, redator ou social media

Participa de projetos específicos. Precisa saber o que está atribuído a ele, quando entregar, e acompanhar comentários ou atualizações de tarefas. Não participa da gestão global da plataforma, mas precisa de clareza nas tarefas atribuídas e visão de progresso.

---

## Jornada do Usuário

### Rick (autônomo / gerente de projeto)

1. Cria um novo projeto ou seleciona um modelo de pipeline existente
2. Cadastra tarefas conforme escopo acordado com o cliente
3. Atribui tarefas a colaboradores (se houver)
4. Controla o tempo de execução via play/pause
5. Acompanha andamento e responde comentários (lista ou Kanban)
6. Notifica o cliente para validação ou revisão
7. Gera relatórios com horas gastas e entregas realizadas
8. Usa os dados para ajustar escopo, prever gargalos ou justificar horas extras

### Cliente

1. Recebe link ou convite para acompanhar seu projeto
2. Acessa painel com visão simplificada (lista ou Kanban)
3. Visualiza tarefas em andamento, revisa entregas e aprova ou solicita alterações
4. Acompanha o uso das horas contratadas por mês ou por projeto
5. Baixa relatórios ou extratos, se necessário

### Colaborador

1. Recebe notificação de nova tarefa atribuída
2. Visualiza descrição, prazos e materiais anexados
3. Inicia execução e registra tempo (caso tenha acesso ao controle)
4. Marca como concluído e adiciona observações
5. Acompanha feedback e itera se necessário

---

## Métricas e KPIs

* % de tarefas aprovadas sem retrabalho
* % de entregas feitas dentro do prazo
* Média de horas gastas por tipo de tarefa
* % de horas contratadas efetivamente utilizadas no mês
* Redução no tempo médio de aprovação pelo cliente
* Engajamento dos usuários (clientes que acessaram o painel no mês)
* Retorno por cliente (volume de tarefas x tempo investido)
* Tempo médio de resposta para ajustes ou validações
* Quantidade de tarefas concluídas por projeto/mês
* Volume de tarefas criadas a partir de modelos

---

## Sucesso e Critérios de Validação

* O sistema está sendo utilizado como ferramenta principal de gestão de tarefas, substituindo planilhas, WhatsApp e e-mails soltos.
* Os clientes acessam regularmente seus painéis para aprovar e acompanhar tarefas, reduzindo o número de mensagens diretas para atualizações.
* As tarefas seguem um fluxo padronizado e são criadas a partir de modelos personalizados para cada tipo de serviço.
* Os relatórios são usados como base para tomada de decisão, cobrança de horas, previsibilidade de entregas e alinhamento com o cliente.
* O controle de tempo por tarefa está funcionando de forma estável e sendo utilizado como principal referência de produtividade.
* A comunicação com colaboradores e clientes acontece de forma estruturada dentro do sistema.
