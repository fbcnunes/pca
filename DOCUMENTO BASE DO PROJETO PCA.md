# DOCUMENTO BASE DO PROJETO  
## Sistema de Criação e Gestão do Plano de Contratações Anual (PCA) – SEPLAD

**Versão:** 1.0  
**Status:** Baseline Funcional Consolidado  
**Órgão:** Secretaria de Planejamento e Administração – SEPLAD  

---

## 1. Contexto Institucional

A Secretaria de Planejamento e Administração (SEPLAD) necessita de um sistema corporativo para **planejar, consolidar, validar, acompanhar e versionar** o Plano de Contratações Anual (PCA), conforme a Lei nº 14.133/2021, o Decreto Estadual nº 2.227/2022 e a Portaria de Governança das Contratações da SEPLAD.

O sistema deverá refletir fielmente a **governança institucional definida**, o **organograma da SEPLAD** e as competências atribuídas às unidades, em especial:

- Diretoria de Administração e Finanças (DAF)
- Núcleo de Planejamento (NPLAN)
- Secretarias Adjuntas
- Gabinete do Secretário

---

## 2. Objetivo do Sistema

Desenvolver um **Sistema de Gestão do PCA da SEPLAD** que permita:

- Coletar demandas de contratação/aquisição das unidades administrativas;
- Submeter as demandas a **validação hierárquica estruturada**;
- Consolidar o PCA de forma centralizada pela DAF;
- Permitir acompanhamento contínuo pelo NPLAN;
- Versionar, aprovar e auditar o PCA;
- Garantir rastreabilidade, transparência e segurança jurídica.

---

## 3. Premissas e Princípios

- O **demandante informa a necessidade**, não o enquadramento estratégico (PPA);
- A **DAF é a instância central de consolidação** do PCA;
- O **NPLAN acompanha**, mas não valida nem consolida;
- O **PPA não é informado pelo demandante**;
- A governança deve ser **simples para o usuário** e robusta para a gestão;
- Toda decisão relevante deve ser **registrada e auditável**.

---

## 4. Estrutura Organizacional (Referência)

O sistema deve refletir o organograma da SEPLAD, considerando:

- Unidades operacionais (Coordenações, Gerências, Núcleos);
- Diretorias;
- Secretarias Adjuntas;
- Gabinete do Secretário.

A hierarquia organizacional será utilizada para:

- Definição de validadores;
- Fluxos de aprovação;
- Relatórios gerenciais.

---

## 5. Módulos do Sistema (Alto Nível)

### 5.1 Governança e Parametrização
- Criação do ciclo anual do PCA;
- Definição de prazos e calendário;
- Catálogos (categorias, prioridades, status);
- Estrutura organizacional e vínculo de usuários;
- Regras do fluxo de validação.

### 5.2 Cadastro de Demandas (M2)
- Registro padronizado das demandas pelas unidades;
- Campos consolidados (Blocos A a I);
- Estados: Rascunho, Enviada, Devolvida, Validada;
- Anexos e histórico.

### 5.3 Validação das Demandas (M3)
- Validação **conjunta**: Diretoria + Secretaria Adjunta;
- **Covalidação obrigatória pelo Gabinete** para demandas da DAF, DTI e Núcleos;
- Devoluções com pendências e comentários obrigatórios.

### 5.4 Consolidação do PCA (DAF)
- Consolidação das demandas validadas;
- Geração da **versão preliminar** do PCA;
- Organização e agrupamento de itens;
- Gestão de versões.

### 5.5 Acompanhamento (NPLAN)
- Monitoramento do andamento do ciclo;
- Identificação de gargalos e atrasos;
- Apoio ao alinhamento com planejamento institucional.

### 5.6 Versões, Aprovação e Publicação
- Aprovação da versão final do PCA;
- Congelamento de versões aprovadas;
- Geração de nova versão mediante alteração justificada.

### 5.7 Relatórios e Auditoria
- Relatórios por unidade, diretoria e secretaria adjunta;
- Visões por prioridade, categoria e cronograma;
- Trilha de auditoria completa.

---

## 6. Cadastro de Demandas (M2) – Estrutura Oficial

### Bloco A – Identificação Institucional
- Ciclo do PCA (selecionado)  
- Unidade Organizacional (vinculada ao usuário)  
- Área Responsável (quando diferente da unidade)

### Bloco B – Identificação da Demanda
- Título da demanda  
- Descrição do objeto

### Bloco C – Classificação
- Tipo: Compra de bens / Serviço / Solução de TI  
- Natureza: Nova contratação/aquisição / Renovação / Continuada  
- Categoria padronizada

### Bloco D – Justificativa e Interesse Público
- Justificativa da necessidade (relação com atividade finalística e interesse público)
- Prioridade da contratação/aquisição (Alta / Média / Baixa)

### Bloco E – Escopo Preliminar
- Quantidade estimada  
- Escopo básico / observações

### Bloco F – Prazo
- Mês específico em que a contratação é necessária  
- Justificativa do prazo (quando crítico)

### Bloco G – Estimativa de Valor
- Valor estimado  
- Fonte da estimativa

### Bloco H – Responsável
- Nome  
- Cargo/Função  
- Contato institucional

### Bloco I – Anexos
- Documentos de referência (opcional)

---

## 7. Fluxo de Validação (Governança)

### 7.1 Fluxo Geral
1. Unidade cria e envia a demanda;
2. Diretoria valida;
3. Secretaria Adjunta valida (conjuntamente);
4. Se aplicável, Gabinete do Secretário covalida;
5. Demanda validada segue para consolidação.

### 7.2 Regras Essenciais
- Validação conjunta exige aprovação de ambos;
- Qualquer reprovação devolve a demanda;
- Devoluções exigem justificativa;
- Demandas da DAF, DTI e Núcleos exigem covalidação do Gabinete.

---

## 8. Consolidação do PCA

- A **DAF é a única responsável** por consolidar o PCA;
- A consolidação gera uma **versão preliminar**;
- A DAF não reabre mérito técnico validado;
- Exclusões ou adiamentos exigem justificativa registrada.

---

## 9. Acompanhamento (NPLAN)

- O NPLAN acompanha o andamento do ciclo;
- Monitora prazos e gargalos;
- Apoia alinhamento com instrumentos de planejamento;
- Não valida nem consolida demandas.

---

## 10. Catálogo de Status

### Status da Demanda
- Rascunho  
- Enviada para validação  
- Em validação na Diretoria  
- Em validação na Secretaria Adjunta  
- Em covalidação no Gabinete  
- Devolvida para ajustes  
- Validada  

### Status do PCA
- Aguardando consolidação  
- Consolidada pela DAF  
- Excluída do PCA  
- Adiada para exercício futuro  

### Status da Versão do PCA
- Em elaboração  
- Versão preliminar  
- Aprovada  
- Substituída  

---

## 11. Auditoria e Rastreabilidade

- Toda ação relevante gera registro:
  - usuário,
  - data/hora,
  - ação,
  - justificativa;
- Versões aprovadas são imutáveis;
- Alterações geram nova versão.

---

## 12. Encerramento

Este documento constitui o **marco zero oficial** do projeto **Sistema PCA-SEPLAD**.

Qualquer evolução funcional, jurídica ou técnica deverá respeitar integralmente as definições aqui consolidadas.
