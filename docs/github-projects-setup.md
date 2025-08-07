# GitHub Projects Setup Guide - TaskFlow

## 🎯 Objetivo
Configurar GitHub Projects para tracking visual e gestão de sprints do projeto TaskFlow.

---

## 📋 PASSO 1: Criar Project Board

### Navegação:
1. Acesse: https://github.com/rickgomes43/taskflow
2. Clique na aba **"Projects"**
3. Clique **"Link a project"** → **"New project"**
4. Escolha template **"Board"**

### Configurações do Project:
- **Nome:** `TaskFlow - Sprint Board`
- **Descrição:** `Kanban board para acompanhamento de sprints e desenvolvimento do TaskFlow`
- **Visibilidade:** Private (mesmo que o repo)
- **Template:** Board

---

## 🔧 PASSO 2: Configurar Colunas Kanban

### Deletar Colunas Padrão:
- ❌ Todo
- ❌ In Progress  
- ❌ Done

### Criar Novas Colunas:

#### 📋 Backlog
- **Descrição:** Items não priorizados ou futuros
- **Automação:** Manual
- **Limite:** Sem limite

#### 🎯 Sprint Backlog  
- **Descrição:** Items da sprint atual (committed)
- **Automação:** Manual
- **Limite:** 10 items

#### 🚧 In Progress
- **Descrição:** Em desenvolvimento ativo
- **Automação:** Manual → Auto quando assignee definido
- **Limite:** 3 items (WIP limit)

#### 👀 In Review
- **Descrição:** Code review, testing, validation
- **Automação:** Auto quando PR criado
- **Limite:** 5 items

#### ✅ Done
- **Descrição:** Completos e deployados
- **Automação:** Auto quando PR merged
- **Limite:** Sem limite

---

## 📊 PASSO 3: Configurar Custom Fields

### Campos Obrigatórios:

#### 🔢 Story Points
- **Type:** Number
- **Options:** 1, 2, 3, 5, 8 (Fibonacci)
- **Default:** 3

#### 🏃 Sprint
- **Type:** Select
- **Options:** 
  - Sprint 0 - Setup
  - Sprint 1 - Auth Base  
  - Sprint 2 - Auth Advanced
  - Sprint 3 - Entities Base
  - Sprint 4 - User Management
  - Sprint 5 - Projects Base
  - Sprint 6 - Tasks Advanced

#### 🎯 Priority  
- **Type:** Select
- **Options:**
  - 🔥 High (Critical/Blocker)
  - 📈 Medium (Important)
  - 📋 Low (Nice to have)

#### 🏗️ Epic
- **Type:** Text
- **Format:** "Epic: [Nome do Epic]"
- **Examples:** 
  - Epic: Setup e Fundação
  - Epic: Sistema de Autenticação

#### ⏱️ Estimate (Hours)
- **Type:** Number
- **Range:** 0.5 - 40
- **Default:** 4

#### 👤 Developer
- **Type:** Person  
- **Default:** rickgomes43

---

## 📝 PASSO 4: Criar Issues das User Stories

### Template para Criar Issues:

Acesse: https://github.com/rickgomes43/taskflow/issues/new/choose

#### Issue #1: US001 (Histórico)
```
Template: 📝 User Story
Título: US001 - Setup Laravel Sail com Docker
Labels: user-story, sprint-0, enhancement, done
Milestone: Sprint 0
Assignee: rickgomes43

Conteúdo:
## 📝 User Story
**As a** desenvolvedor  
**I want** um ambiente Laravel funcionando em containers Docker  
**So that** eu possa desenvolver o TaskFlow de forma consistente e isolada

## 📋 Story Context
**Epic:** Setup e Fundação  
**Sprint:** Sprint 0  
**Priority:** 🔥 High  
**Estimate:** 4 hours  

**Dependencies:**
- [x] Nenhuma

## ✅ Acceptance Criteria
**Functional Requirements:**
1. [x] Aplicação Laravel executando em container Docker
2. [x] MySQL conectando corretamente com database criado
3. [x] Redis funcionando para cache e sessões

**Quality Requirements:**
4. [x] Comando `sail up` inicia serviços sem erros
5. [x] Comando `sail artisan migrate` executa migrations
6. [x] Página inicial Laravel acessível via navegador
7. [x] Documentação README.md com setup

## 🎯 Status: ✅ COMPLETA
Implementada por James com sucesso. Base para próximas sprints estabelecida.
```

#### Issue #2: US003 (Próxima)
```
Template: 📝 User Story
Título: US003 - Estrutura Base e Documentação
Labels: user-story, sprint-0, enhancement
Milestone: Sprint 0
Assignee: rickgomes43

Conteúdo:
## 📝 User Story
**As a** novo desenvolvedor na equipe  
**I want** estrutura de projeto organizada e documentação clara de setup  
**So that** eu possa começar a contribuir rapidamente com o TaskFlow

## 📋 Story Context
**Epic:** Setup e Fundação  
**Sprint:** Sprint 0  
**Priority:** 🔥 High  
**Estimate:** 3 hours  

**Dependencies:**
- [x] US001: Setup Laravel Sail

## ✅ Acceptance Criteria
**Functional Requirements:**
1. [ ] Estrutura de pastas organizada seguindo padrões Laravel
2. [ ] README.md completo com instruções passo-a-passo
3. [ ] Arquivo .env.example configurado com variáveis necessárias

**Quality Requirements:**
4. [ ] Git configurado com .gitignore adequado para Laravel
5. [ ] Documentação integrada com arquitetura existente em docs/
6. [ ] Novo desenvolvedor consegue setup em < 30 minutos
7. [ ] Todos os comandos documentados executam sem erro

## 🎯 Status: 🎯 PRÓXIMA
Pronta para desenvolvimento após US001.
```

#### Issue #3: US002 (Planejada)
```
Template: 📝 User Story
Título: US002 - Configuração Frontend e Testes
Labels: user-story, sprint-0, enhancement
Milestone: Sprint 0
Assignee: rickgomes43

Conteúdo:
## 📝 User Story
**As a** desenvolvedor  
**I want** Tailwind CSS configurado e estrutura de testes funcionando  
**So that** eu possa desenvolver interfaces modernas e garantir qualidade do código

## 📋 Story Context
**Epic:** Setup e Fundação  
**Sprint:** Sprint 0  
**Priority:** 📈 Medium  
**Estimate:** 5 hours  

**Dependencies:**
- [x] US001: Setup Laravel Sail

## ✅ Acceptance Criteria
**Functional Requirements:**
1. [ ] Tailwind CSS compilando assets CSS corretamente
2. [ ] ShadCN UI componentes base instalados e funcionando
3. [ ] PHPUnit executando testes unitários e feature tests

**Quality Requirements:**
4. [ ] Assets servidos corretamente pelo Laravel
5. [ ] Tests database separado do desenvolvimento
6. [ ] GitHub Actions executando testes automaticamente
7. [ ] Pipeline CI/CD executa em < 5 minutos

## 🎯 Status: 📋 PLANEJADA
Aguardando US001 e US003 para início.
```

---

## 🎯 PASSO 5: Integrar Issues com Project

### Após Criar as Issues:

1. **Acesse o Project Board**
2. **Clique "Add items"** 
3. **Selecione** as 3 issues criadas
4. **Organize nas colunas:**
   - US001 → ✅ Done
   - US003 → 🎯 Sprint Backlog  
   - US002 → 📋 Backlog

### Configure Custom Fields:
Para cada issue, preencha:
- **Story Points:** US001=4, US003=3, US002=5
- **Sprint:** Sprint 0
- **Priority:** US001/US003=High, US002=Medium
- **Epic:** Setup e Fundação
- **Estimate:** Conforme story points

---

## 📅 PASSO 6: Configurar Milestones

### Criar Milestones:
Acesse: https://github.com/rickgomes43/taskflow/milestones

#### Sprint 0 - Setup e Fundação
- **Due date:** [Data fim da semana]
- **Description:** Ambiente de desenvolvimento funcional e replicável
- **Issues:** 3 (US001, US002, US003)

#### Sprint 1 - Autenticação Base (Futuro)
- **Due date:** [+2 semanas]
- **Description:** Sistema básico de login/logout e papéis
- **Issues:** [A criar]

#### Sprint 2 - Autenticação Avançada (Futuro)
- **Due date:** [+4 semanas]  
- **Description:** Google OAuth e sistema completo de papéis
- **Issues:** [A criar]

---

## 📊 PASSO 7: Configurar Automações

### Automations Recomendadas:

#### Auto-assign to Project
- **When:** Issue created with label "user-story"
- **Then:** Add to "TaskFlow - Sprint Board"

#### Move to In Review  
- **When:** Pull request created
- **Then:** Move linked issues to "👀 In Review"

#### Move to Done
- **When:** Pull request merged
- **Then:** Move linked issues to "✅ Done"

#### Set Sprint from Milestone
- **When:** Issue assigned to milestone
- **Then:** Set Sprint field automatically

---

## 🎯 RESULTADO FINAL

### Board Visual Completo:
```
📋 Backlog     🎯 Sprint Backlog    🚧 In Progress    👀 In Review    ✅ Done
                                                                      
               US003 (3h)                                           US001 (4h)
                                                                    ✅ Laravel Sail
📋 US002 (5h)   🎯 Estrutura                                       
   Frontend        & Docs                                         
   & Testes       Priority: High                                  
```

### Métricas Automáticas:
- **Sprint Progress:** 33% (1/3 stories done)
- **Story Points:** 4/12 completed  
- **Burndown:** Visual no dashboard
- **Velocity:** Tracking por sprint

---

## 🚀 Próximos Passos

Após configurar o board:
1. **Mover US003** para "In Progress" quando James começar
2. **Criar issues** dos próximos sprints
3. **Usar board** para dailies e planning
4. **Tracking** de métricas de sprint

---

*Setup Guide criado por: Sarah (Product Owner)*  
*Data: 2025-08-07*  
*Repo: https://github.com/rickgomes43/taskflow*