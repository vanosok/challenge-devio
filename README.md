# Projeto PDV - Fast Food / Back-end
Este projeto foi realizado como solução para o desafio de back-end de PDV - Fast Food.

## Tecnologias Utilizadas
- PHP
- FDPF (biblioteca para geração de PDF)
- Banco de Dados
- Documentação da API

Foram criados alguns endpoints para atender as necessidades do desafio. A documentação completa pode ser encontrada no [neste link](https://documenter.getpostman.com/view/15706775/2s935ivSJ1) acabei utilizando postman por questão do tempo porém se necessário posso documentar no swagger. 

## Banco de Dados
O banco de dados utilizado está documentado na pasta "documentation" do projeto. A conexão com o banco pode ser configurada no arquivo "config".

Caso queira acessar o banco para poder dar uma olhada nas querys e verificar como os dados estão sendo armazenados clique [neste link](http://54.87.158.220/phppgadmin/). Vou enviar o usuário e a senha necessário para acessar o banco no email do desafio junto com o github.


## Resumo do Desafio
Este desafio foi uma oportunidade para colocar em prática o conhecimento adquirido ao longo do tempo. Optei por utilizar o PHP e um banco de dados para manter a organização e funcionalidade do sistema.

Para atender ao requisito de impressão, foi criada uma função para gerar um arquivo PDF com o pedido. Todos os PDFs gerados são salvos na pasta raiz do projeto e podem ser baixados pelo usuário.

O projeto foi desenvolvido seguindo o conceito SOLID para manter o código organizado e de fácil manutenção. Além disso, foram adicionadas funcionalidades extras, como login/logout e controle de usuários, para melhorar a experiência do usuário e facilitar o controle dos pedidos.

## Deploy 
O deploy do projeto foi feito na cloud , em uma maquina ec2 da amazon foi feito o deploy do banco e também da api.

