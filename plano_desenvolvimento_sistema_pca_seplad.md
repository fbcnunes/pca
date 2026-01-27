# Plano de Desenvolvimento Step by Step
## Sistema PCA – SEPLAD

Stack tecnológica:
PHP 8.2
Laravel
Tailwind CSS
MySQL 8
Apache

Layout padrão:
Sidebar fixa à esquerda
Conteúdo principal à direita

---

## 1. Preparação do ambiente

- Criar repositório Git e definir padrão de branches
- Instalar PHP, Composer, Node LTS, MySQL e Apache
- Criar projeto Laravel
- Configurar arquivo .env
- Configurar VirtualHost no Apache apontando para /public
- Configurar migrations e seeders iniciais
- Integrar Tailwind CSS ao build do Laravel
- Criar layout base com sidebar, topbar e área de conteúdo

---

## 2. Fundamentos do sistema

- Implementar autenticação e controle de sessão
- Implementar perfis institucionais e permissões
- Criar middleware de autorização
- Implementar auditoria central de ações
- Padronizar logs de negócio com usuário, data hora, ação e justificativa

---

## 3. Modelagem inicial do banco de dados

Tabelas núcleo:

- usuarios
- perfis
- permissoes
- perfil_usuario
- permissao_perfil
- unidades_organizacionais
- ciclos_pca
- catalogos_categorias
- catalogos_prioridades
- catalogos_tipos_demanda
- catalogos_naturezas
- status_demandas
- status_pca
- status_versoes_pca
- demandas
- anexos_demandas
- historico_demandas
- validacoes_demandas
- versoes_pca
- itens_pca
- logs_auditoria

---

## 4. Estrutura de navegação em estilo sidebar

### Demandante
- Dashboard
- Minhas demandas (unidade do usuário pré-definida)
- Nova demanda (usa ciclo + unidade do usuário)
- Rascunhos
- Devolvidas
- Enviadas
- Anexos
- Perfil

### Diretoria e Secretaria Adjunta
- Dashboard
- Caixa de validação
- Pendências
- Histórico de decisões
- Relatórios rápidos

### Gabinete
- Dashboard
- Covalidação
- Pendências do Gabinete
- Histórico de covalidações

### DAF
- Dashboard
- Consolidação do PCA
- Demandas validadas
- Versões do PCA
- Itens excluídos e adiados
- Publicação
- Auditoria

### NPLAN
- Dashboard
- Acompanhamento do ciclo
- Gargalos e atrasos
- Visões por unidade
- Relatórios

### Administrador
- Governança
- Ciclos do PCA
- Calendário e prazos
- Catálogos
- Estrutura organizacional
- Usuários e vínculos
- Regras de fluxo
- Auditoria do sistema

---

## 5. Componentes padrão de interface

- Sidebar fixa com agrupamento por módulo
- Topbar com ciclo ativo e usuário logado
- Tabelas com paginação, filtros e ordenação
- Formulários estruturados por blocos A a I
- Badges de status
- Timeline de histórico
- Modais de devolução e justificativa
- Upload de anexos

---

## 6. Módulo 1 – Governança e parametrização

- CRUD de ciclos do PCA
- CRUD de catálogos institucionais
- Cadastro da estrutura organizacional hierárquica
- Vínculo de usuários a unidades e perfis
- Definição de regras de fluxo e covalidação

---

## 7. Módulo 2 – Cadastro de demandas

- Cadastro completo da demanda com Blocos A a I
- Estados da demanda conforme governança
- Validação de campos obrigatórios
- Upload e gerenciamento de anexos
- Histórico automático de eventos
- Listagem e filtros de demandas

---

## 8. Módulo 3 – Validação das demandas

- Caixa de validação por perfil
- Aprovação e devolução com justificativa obrigatória
- Validação conjunta Diretoria e Secretaria Adjunta
- Covalidação do Gabinete quando aplicável
- Controle de transições de status
- Exibição de timeline de decisões

---

## 9. Módulo 4 – Consolidação do PCA pela DAF

- Visualização de demandas validadas
- Agrupamento por categoria, prioridade, mês e unidade
- Geração de versão preliminar do PCA
- Exclusão e adiamento de itens com justificativa
- Controle de status dos itens do PCA

---

## 10. Módulo 5 – Acompanhamento pelo NPLAN

- Painel de acompanhamento do ciclo
- Identificação de gargalos e atrasos
- Visões consolidadas por unidade e diretoria
- Alertas de prazos
- Restrições de permissão para validação e consolidação

---

## 11. Módulo 6 – Versões, aprovação e publicação

- Aprovação da versão final do PCA
- Congelamento de versões aprovadas
- Criação de nova versão com justificativa
- Marcação de versões substituídas
- Exportação em PDF e planilha

---

## 12. Módulo 7 – Relatórios e auditoria

Relatórios:
- Por unidade
- Por diretoria e secretaria adjunta
- Por prioridade e categoria
- Por cronograma e status

Auditoria:
- Trilha completa de ações
- Filtros por usuário, período e entidade
- Exportação de dados

---

## 13. Segurança e qualidade

- Middleware por permissão
- Policies por entidade
- Proteção de acesso entre unidades
- Limite de requisições em rotas sensíveis
- Backups automatizados
- Testes unitários e de fluxo

---

## 14. Implantação

- Ambientes de desenvolvimento, homologação e produção
- Pipeline de integração contínua
- Deploy versionado no Apache
- Execução controlada de migrations
- Monitoramento de logs e erros
- Rotina de backup e retenção

---

## 15. Roteiro de sprints

Sprint 1
- Ambiente, autenticação, layout base, auditoria e estrutura organizacional

Sprint 2
- Governança, ciclos do PCA, catálogos e regras de fluxo

Sprint 3
- Cadastro de demandas completo

Sprint 4
- Validação das demandas e covalidação

Sprint 5
- Consolidação do PCA pela DAF

Sprint 6
- Acompanhamento pelo NPLAN

Sprint 7
- Versões, aprovação e publicação

Sprint 8
- Relatórios, auditoria e reforço de segurança
