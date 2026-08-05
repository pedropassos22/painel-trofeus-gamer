Este arquivo responde:

Como o sistema foi construído?

Conteúdo:

Estrutura de pastas
Fluxo da aplicação
Bootstrap
Database
PDO
Uploads
Organização dos módulos
Padrões de código

----        ----        ----        ----        ----        ----
----        ----        ----        ----        ----        ----
----        ----        ----        ----        ----        ----

# Arquitetura do Projeto

## Nome

Painel de Troféus Gamer

---

## Objetivo

Sistema para cadastro de jogos concluídos e integração futura com um painel físico baseado em Arduino.

---

# Tecnologias

- PHP
- MySQL
- PDO
- HTML
- CSS
- JavaScript
- Laragon

---

# Estrutura

app/
config/
database/
docs/
public/
storage/

---

# Banco de Dados

O banco de dados é considerado parte do projeto.

Toda alteração deverá ser refletida em:

database/schema.sql

Nunca depender apenas do banco local.

---

# Uploads

Todos os uploads ficam em:

storage/uploads/

Nunca dentro da pasta public.

---

# Arquivos Públicos

Somente a pasta public poderá ser acessada pelo navegador.

---

# Configurações

Todas as configurações ficam no arquivo:

.env

---

# Padrões

- Um arquivo possui apenas uma responsabilidade.
- Todo acesso ao banco será feito via PDO.
- Toda consulta utilizará Prepared Statements.
- O projeto será organizado para produção desde o início.