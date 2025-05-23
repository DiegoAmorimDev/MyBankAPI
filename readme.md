# MyBank API - Laravel com DDD e Clean Architecture

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![DDD & Clean Architecture](https://img.shields.io/badge/Architecture-DDD_Clean-6DB33F?style=for-the-badge)](https://martinfowler.com/tags/domain%20driven%20design.html)

Esta API bancária foi desenvolvida como um exercício técnico, aplicando os princípios do **Domain-Driven Design (DDD)** e da **Clean Architecture** utilizando o framework **Laravel**. O objetivo é demonstrar uma estrutura de projeto que favorece a manutenibilidade, testabilidade e um claro alinhamento com as regras de negócio.

## Arquitetura e Design

A MyBank API foi estruturada para separar claramente as responsabilidades e isolar o núcleo do domínio de detalhes de infraestrutura.

### Domain-Driven Design (DDD) no Laravel

O DDD foi aplicado através dos seguintes conceitos e estruturas no projeto:

1.  **Contextos Delimitados (Bounded Contexts)**: A API foi organizada em contextos de negócio distintos, cada um com seu próprio modelo e linguagem ubíqua. Os principais contextos implementados são:
    *   **Auth**: Responsável pela autenticação (atualmente com um endpoint de login mockado).
        *   Localização: `app/Domain/Auth/`
    *   **PixKey**: Gerencia chaves PIX (criação, consulta).
        *   Localização: `app/Domain/PixKey/`
    *   **PixTransaction**: Lida com transações PIX (criação, consulta).
        *   Localização: `app/Domain/PixTransaction/`

2.  **Camada de Domínio (`app/Domain`)**: Esta camada é o coração da aplicação e contém:
    *   **Entidades**: Objetos com identidade única que encapsulam lógica de negócio (ex: `app/Domain/PixKey/Entities/PixKey.php`, `app/Domain/PixTransaction/Entities/PixTransaction.php`).
    *   **Objetos de Valor (Value Objects)**: Objetos imutáveis que representam conceitos descritivos e encapsulam validações e comportamento (ex: `app/Domain/PixKey/ObjectValues/KeyType.php`, `app/Domain/PixTransaction/ObjectValues/Amount.php`). A pasta `ObjectValues` foi criada dentro de cada contexto do domínio para abrigá-los.
    *   **Interfaces de Repositório (Ideal)**: Definiriam contratos para persistência de dados, permitindo que o domínio permaneça independente da tecnologia de armazenamento (a ser implementado com persistência real).

3.  **Linguagem Ubíqua**: Os nomes de classes, métodos e variáveis buscam refletir a linguagem do domínio bancário e PIX.

### Clean Architecture no Laravel

A Clean Architecture foi adaptada para a estrutura do Laravel da seguinte forma:

1.  **Camadas Concêntricas**:
    *   **Domínio (`app/Domain`)**: Camada mais interna, sem dependências externas.
    *   **Aplicação (Use Cases - a ser formalizada)**: Orquestraria os fluxos de dados e usaria o domínio. Atualmente, parte dessa lógica reside nos controllers.
    *   **Infraestrutura (`app/Infrastructure`)**: Camada mais externa, contendo:
        *   **Controllers (`app/Infrastructure/Http/Controllers/Api/`)**: Adaptadores que recebem requisições HTTP, validam dados e interagem com a camada de aplicação/domínio.
        *   **Persistência (`app/Infrastructure/Persistence/`)**: Onde as implementações de repositório (ex: Eloquent, Mocks) residiriam. Atualmente, os controllers usam mocks em memória.
        *   **Rotas (`routes/api.php`)**: Definem os endpoints da API.

2.  **Regra de Dependência**: As dependências fluem para dentro. A Infraestrutura depende do Domínio, mas o Domínio não conhece a Infraestrutura.

## Como Rodar o Projeto

Você pode executar este projeto localmente (sem Docker) ou utilizando Docker.

### Opção 1: Execução Local (Sem Docker)

**Pré-requisitos:**
*   PHP >= 8.1
*   Composer
*   Extensões PHP necessárias para Laravel (mbstring, openssl, pdo, xml, etc.)

**Passos:**

1.  **Clone o repositório ou descompacte o projeto** em um diretório local.
    ```bash
    # Exemplo:
    # git clone <url_do_repositorio> MyBankAPI-DDD
    cd MyBankAPI-DDD
    ```

2.  **Instale as dependências do PHP** com o Composer:
    ```bash
    composer install
    ```

3.  **Configure o arquivo de ambiente (`.env`)**:
    Copie o arquivo de exemplo e gere a chave da aplicação.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Revise o arquivo `.env` para outras configurações se necessário (ex: `APP_URL`).

4.  **Ajuste as permissões** (se necessário, especialmente em Linux/macOS):
    Os diretórios `storage` e `bootstrap/cache` precisam de permissão de escrita.
    ```bash
    sudo chmod -R 775 storage bootstrap/cache
    # Pode ser necessário ajustar o proprietário para o usuário do servidor web
    # sudo chown -R www-data:www-data storage bootstrap/cache
    ```

5.  **Inicie o servidor de desenvolvimento do Laravel**:
    ```bash
    php artisan serve
    ```
    Por padrão, a API estará acessível em `http://localhost:8000`.

6.  **Limpe o cache de rotas (se encontrar problemas com rotas)**:
    ```bash
    php artisan route:clear
    ```

Para instruções mais detalhadas sobre a execução local, consulte o arquivo `LOCAL_EXECUTION_GUIDE.md` fornecido anteriormente.

### Opção 2: Execução com Docker

**Pré-requisitos:**
*   Docker e Docker Compose instalados.

**Passos:**

1.  **Clone o repositório ou descompacte o projeto**.

2.  **Configure o arquivo de ambiente (`.env`)** na raiz do projeto:
    Copie o `.env.example` para `.env`.
    ```bash
    cp .env.example .env
    ```
    A chave da aplicação (`APP_KEY`) será gerada dentro do container ou pode ser gerada localmente antes se você tiver PHP.

3.  **Construa e inicie os containers** usando Docker Compose:
    No diretório raiz do projeto (onde está o `docker-compose.yml`), execute:
    ```bash
    docker-compose up -d --build
    ```

4.  **Gere a chave da aplicação (se ainda não existir no `.env`)**:
    ```bash
    docker-compose exec app php artisan key:generate
    ```

5.  **Ajuste as permissões dentro do container (se necessário)**:
    ```bash
    docker-compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    docker-compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    ```

A API estará acessível em `http://localhost:8000` (conforme configurado no `docker-compose.yml` para o Nginx).

Para instruções mais detalhadas sobre o uso do Docker, consulte os arquivos `DOCKER_USAGE_LINUX.md` e `DOCKER_USAGE_WINDOWS.md` fornecidos anteriormente.

## Endpoints da API

*   **Autenticação**:
    *   `POST /api/auth/login`
*   **Chaves PIX**:
    *   `POST /api/pix/keys`
    *   `GET /api/pix/keys/{id}`
*   **Transações PIX**:
    *   `POST /api/pix/transactions`
    *   `GET /api/pix/transactions/{id}`

Consulte a mensagem anterior com os exemplos de JSON para cada rota.

## Progresso e Evolução

| Etapa Concluída | Descrição Técnica | Relação com DDD/Clean Arch |
|-----------------|-------------------|----------------------------|
| Estrutura Base  | Adaptação da estrutura do framework Laravel. Camadas `Domain`, `Application` (conceitual), `Infrastructure` adicionadas. | Core Domain e separação de camadas iniciada. |
| Contexto Auth   | Criação do `AuthController` e `ObjectValues` como `Email`, `Password`. | Contexto Delimitado `Auth` com VOs. |
| Contexto PixKey | Implementação da entidade `PixKey`, seus `ObjectValues` (`AccountId`, `KeyType`, `KeyValue`), `PixKeyController` e rotas. | Contexto Delimitado `PixKey` com Entidade e VOs. Controller na Infraestrutura. |
| Contexto PixTransaction | Implementação da entidade `PixTransaction`, seus `ObjectValues` (`Amount`, `Description`, `TransactionId`, `Timestamp`), `PixTransactionController` e rotas. | Contexto Delimitado `PixTransaction` com Entidade e VOs. Controller na Infraestrutura. |
| Dockerização    | Criação de `Dockerfile` e `docker-compose.yml` para ambiente de desenvolvimento. | Facilita a execução e o deploy, isolando o ambiente da aplicação. |
| Documentação    | Criação de guias de uso Docker, execução local e documentação técnica. |  Disseminação do conhecimento sobre a arquitetura e uso. |
| Application Layer    | Isolamento dos UseCases e DTOs na camada de aplicação |  Desacoplamento de camadas |


**Nota sobre Persistência**: Atualmente, a camada de persistência está sendo simulada com dados mockados em memória.. Para testes persistentes e uma aplicação funcional, a implementação de repositórios reais com um banco de dados (ex: SQLite, MySQL) é um próximo passo crucial.

---

## 🔍 Referências

- [Domain-Driven Design - Martin Fowler](https://martinfowler.com/tags/domain%20driven%20design.html)
- [Clean Architecture - Uncle Bob](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Laravel Documentation](https://laravel.com/docs)
- [Object Calisthenics](https://williamdurand.fr/2013/06/03/object-calisthenics/)

---

## 🛠️ Próximos Passos (Sugestões)

- Implementar persistência real com banco de dados (PostgreSQL, SQL Server).
- Criar interfaces de Repositório no Domínio e implementações na Infraestrutura.
- Implementar autenticação robusta com tokens (Sanctum ou JWT).
- Formalizar a Camada de Aplicação com Serviços de Aplicação (Use Cases)
- Adicionar testes unitários, de integração e de feature.
- Gerar documentação da API (Swagger/OpenAPI).

---
