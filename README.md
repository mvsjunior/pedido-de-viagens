# Módulo para Aprovação de Solicitações

# Índice
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Instalação e Execução com Docker](#instalação-e-execução-com-docker)
- [Regra de Negócio — Sistema de Aprovação de Solicitações de Viagem](#regra-de-negócio--sistema-de-aprovação-de-solicitações-de-viagem)

# Visão Geral / Tecnologias Utilizadas

Este projeto consiste em um sistema de gestão e aprovação de solicitações de viagem corporativas, permitindo que colaboradores realizem pedidos e que gestores os aprovem ou cancelem conforme regras de negócio definidas.

## Tecnologias Utilizadas

- **PHP 8.3** — Linguagem principal utilizada no backend.
- **Laravel** — Framework responsável pela estrutura da API, autenticação, regras de negócio e envio de e-mails.
- **MySQL 8** — Banco de dados para armazenamento das solicitações e usuários.
- **JWT Auth** — Autenticação baseada em tokens JWT.
- **Docker** — Contêinerização da aplicação para facilitar instalação e execução.
- **Nginx** — Servidor web utilizado para servir a aplicação.
- **Composer** — Gerenciamento de dependências PHP.
- **PHPUnit** — Ferramenta de testes automatizados.
- **Mailables do Laravel** — Envio de notificações por e-mail quando uma solicitação é aprovada ou cancelada.

# Instalação e Execução com Docker

1. Acesse o diretório `/docker` na raiz do projeto.

2. Execute o build dos contêineres:
```bash
docker-compose build
```
3. Suba o ambiente:
```bash
docker-compose up -d
```
4. Instale as dependências do Laravel (executado dentro do container `app`):
```bash
docker exec -it laravel-app composer install
```
5. Gere a chave da aplicação:
```bash
docker exec -it laravel-app php artisan key:generate
```
6. Rode as migrations:
```bash
docker exec -it laravel-app php artisan migrate
```
A aplicação estará disponível em: **[http://localhost:8000]()**

---

## Organização de Diretórios

A estrutura do projeto segue um padrão baseado em **Domínios (Domain-Driven Design Lite)**, organizando regras de negócio, controllers, ações e modelos de forma independente por contexto. Isso torna o sistema mais modular, escalável e fácil de manter.

A seguir, a estrutura do diretório `app/Domains` e a função de cada parte.
```bash
app/Domains\
├── Auth\
│ ├── Actions\
│ │ └── DTO\
│ ├── DTO\
│ ├── Exceptions\
│ └── Http\
│ ├── Controllers\
│ └── Requests\
└── Travel\
├── Actions\
├── DTO\
├── Exceptions\
├── Factories\
├── Http\
│ ├── Controllers\
│ └── Requests\
├── Models\
├── Policies\
├── routes\
└── ValueObjects

```

## Descrição dos Diretórios

### **Domínio Auth**
Responsável por tudo relacionado à autenticação e autorização.

- **Actions**  
  Contém classes que executam ações específicas (ex: login, registro).  
  - **Actions/DTO**: Objetos de transferência de dados usados pelas actions.

- **DTO**  
  Objetos imutáveis usados para transportar dados entre camadas.

- **Exceptions**  
  Exceções específicas do domínio de autenticação.

- **Http/Controllers**  
  Controladores que tratam requisições relacionadas a login, logout e registro.

- **Http/Requests**  
  Form Requests responsáveis por validações específicas do domínio de autenticação.

---

### **Domínio Travel**
Responsável pela gestão das solicitações de viagem e processo de aprovação.

- **Actions**  
  Contém classes que executam ações da regra de negócio (abrir solicitação, aprovar, cancelar).

- **DTO**  
  Objetos de transferência que organizam dados de entrada e saída entre camadas do domínio.

- **Exceptions**  
  Exceções específicas das regras de negócio de viagem (ex: solicitação já aprovada).

- **Factories**  
  Classes utilizadas para gerar instâncias de modelos ou objetos complexos.

- **Http/Controllers**  
  Controladores responsáveis por receber requisições da API do domínio de viagens.

- **Http/Requests**  
  Form Requests que centralizam validações (ex: validação de cancelamento).

- **Models**  
  Modelos Eloquent relacionados ao domínio (ex: TravelRequest).

- **Policies**  
  Regras de autorização para verificar se um usuário pode aprovar, cancelar ou editar solicitações.

- **routes**  
  Arquivos de rotas específicos do domínio, mantendo o roteamento isolado e organizado.

- **ValueObjects**  
  Objetos de valor que representam conceitos imutáveis e específicos do domínio (ex: datas de viagem, motivos de cancelamento).

---

# Regra de Negócio — Sistema de Aprovação de Solicitações de Viagem (Travel)

Este módulo define o fluxo de criação, aprovação e cancelamento de solicitações de viagem, incluindo permissões, estados da solicitação e ações automáticas.

---

## Papéis de Usuário

| Papel          | Permissões |
|----------------|------------|
| **Common User** | Criar, listar, visualizar e editar apenas suas próprias solicitações pendentes. Não pode aprovar ou cancelar. |
| **Manager**     | Aprovar ou cancelar solicitações pendentes de qualquer usuário. |
| **Admin**       | Pode aprovar ou cancelar qualquer solicitação, independentemente do autor ou estado. |

---

## Ciclo de Vida da Solicitação

### **1. pending**
- Estado inicial ao criar uma solicitação.
- Pode ser editada pelo próprio solicitante.
- Pode ser aprovada ou cancelada por Manager ou Admin.

### **2. approved**
- Estado definido por Manager ou Admin.
- Registra:
  - `approved_by`
  - `approved_at`
- Dispara envio de e-mail automático ao solicitante.

### **3. canceled**
- Estado definido por Manager ou Admin.
- Exige:
  - `cancel_reason` (obrigatório)
  - `canceled_by`
- Dispara envio de e-mail automático ao solicitante.

---

## Regras de Autorização

### Common User
- Pode criar solicitações.
- Pode visualizar apenas as suas solicitações.
- Não pode aprovar ou cancelar.

### Manager
- Pode aprovar ou cancelar solicitações pendentes.
- Cancelamento exige `cancelReason`.
- Não pode modificar solicitações já aprovadas ou canceladas.

### Admin
- Permissão total para aprovar e cancelar.
- Sem restrições por autor ou estado.

---

## Envio Automático de E-mail

O sistema envia e-mails ao solicitante sempre que houver decisão sobre a solicitação:

### Quando aprovada
- Informações enviadas:
  - Novo status
  - Destino
  - Datas
  - Nome do aprovador

### Quando cancelada
- Informações enviadas:
  - Novo status
  - Motivo do cancelamento
  - Informações gerais da solicitação

O envio ocorre imediatamente após a atualização no banco de dados.

---

## Registro de Auditoria

| Ação          | Campos registrados              |
|---------------|----------------------------------|
| Aprovação     | `status`, `approved_by`, `approved_at` |
| Cancelamento  | `status`, `cancel_reason`, `canceled_by`, `canceled_at` |

---

## Resumo das Regras

- Solicitações começam sempre em **pending**.
- Apenas Manager e Admin podem aprovar ou cancelar.
- Cancelamento sempre exige motivo.
- O solicitante é sempre notificado por e-mail.

---

