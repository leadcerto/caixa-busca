/**
 * MODEL: ImovelImportado
 * 
 * IA (Antigravity): Este model representa a tabela onde os dados do CSV serão salvos.
 * 
 * Instruções para a IA (Mapeamento/ORM):
 * 1. Defina os campos básicos: id_caixa (Unique), preco, endereco, cidade, estado.
 * 2. Defina as colunas que vieram do Parse do CSV: tipo_imovel, quartos, vagas, banheiros (Todos devem ser indexados no banco de dados para acelerar buscas).
 * 3. O campo da imagem principal será apenas uma coluna 'url_imagem_caixa' (String/Varchar).
 * 4. Crie o schema/migration correspondente a este model.
 */

class ImovelImportado {
    // IA: Preencher com a sintaxe do ORM utilizado no projeto (Sequelize, Prisma, Eloquent, Mongoose, etc).
}

// export default ImovelImportado;
