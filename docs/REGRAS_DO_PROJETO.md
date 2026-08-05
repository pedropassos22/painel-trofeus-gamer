Este será praticamente nossa "constituição".

----      ----      ----      ----      ----
----      ----      ----      ----      ----
----      ----      ----      ----      ----


# Regras do Projeto

## Permanentes

00 - Estrutura pronta para produção.
01 - Informar o estado da janela de contexto.
02 - Explicar tudo passo a passo.
03 - Banco recriável por arquivos versionados.
04 - Um arquivo, uma responsabilidade.
05 - Toda decisão arquitetural deve ser documentada.
06 - O site deve ser responsivo desde o início, priorizando: Desktop → Mobile → Tablet.
07 - Nunca utilizar caminhos ou URLs fixos (hardcoded). Todo caminho deve utilizar constantes
08 - O bootstrap.php será o único ponto de inicialização da aplicação.
09 - Toda decisão de arquitetura substitui definitivamente a anterior.
10 - Toda alteração importante no projeto deve atualizar a documentação correspondente.

                    Exemplos:

                    | Alteração           | Documento            |
                    | ------------------- | -------------------- |
                    | Nova fase           | FASES.md             |
                    | Nova regra          | REGRAS_DO_PROJETO.md |
                    | Mudança estrutural  | ARQUITETURA.md       |
                    | Nova funcionalidade | ROADMAP.md           |
                    | Alteração concluída | CHANGELOG.md         |

11 - 

## Temporárias

01 - Nome do banco de dados:
painel_trofeus-gamer



----        ----        ----        ----        ----        ----
----        ----        ----        ----        ----        ----
----        ----        ----        ----        ----        ----



CONSOLIDADA:
# Regras do Projeto

## Permanentes

### 00

Estrutura pronta para produção, mesmo que o sistema seja simples.

---

### 01

Ao final de cada resposta informar o estado da janela de contexto.

---

### 02

Assumir que o desenvolvedor nunca programou.
Sempre informar:

- qual arquivo criar
- qual arquivo editar
- onde apagar
- onde colar
- explicar o motivo

---

### 03

O banco de dados deve ser totalmente recriável através dos arquivos existentes no projeto.

Nunca depender do banco local.

---

### 04

Cada arquivo deve possuir apenas uma responsabilidade.

---

### 05

Toda decisão arquitetural importante deverá ser documentada.

---

### 06

O site deverá ser responsivo desde o início.

Prioridade:

1 Desktop

2 Mobile

3 Tablet

---

### 07

Nunca utilizar caminhos hardcoded.

Sempre utilizar constantes da aplicação.

---

### 08

O bootstrap.php será o único ponto de inicialização do sistema.

---

### 09

Quando uma decisão arquitetural substituir outra, a decisão antiga deixa de existir.
Nunca manter duas arquiteturas concorrentes.

---

## Temporárias

### T01

Nome oficial do banco:

painel_trofeus-gamer