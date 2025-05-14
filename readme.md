# MyBank API - Laravel com DDD e Clean Architecture

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![DDD](https://img.shields.io/badge/Architecture-DDD_Clean-6DB33F?style=for-the-badge)](https://martinfowler.com/tags/domain%20driven%20design.html)

Repositório para o desafio técnico de API bancária, implementando progressivamente:
- **Domain-Driven Design** (Separação clara de domínios: Account, Pix, Auth)
- **Clean Architecture** (Camadas isoladas: Domain → Application → Infrastructure)
- **Laravel Structure** (Adaptação do framework para arquitetura hexagonal)

## Progresso das Alterações

| Commit | Descrição Técnica | Relação com DDD/Clean Arch |
|--------|------------------|---------------------------|
| `35b4ca4` | Estrutura inicial do Laravel | Baseline padrão |
| `current` | Camadas `Domain, Application, Infrastructure` adicionadas, domínios com entidades Account e Value Objects | Core Domain implementation |


---

## 🔍 Referências

- [Domain-Driven Design - Martin Fowler](https://martinfowler.com/tags/domain%20driven%20design.html)
- [Clean Architecture - Uncle Bob](https://8thlight.com/blog/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Laravel Documentation](https://laravel.com/docs)

---

## 🛠️ Em desenvolvimento

Projeto em evolução contínua. Em breve:
- Rotas de autenticação e tokens.
- Finalizando dos bounded contexts.
- Definição dos casos e uso.
- Documentação técnica

---

