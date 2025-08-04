let produtos = JSON.parse(localStorage.getItem('produtos')) || [];
let editIndex = null;

function salvarProduto() {
    const nome = document.getElementById('nome').value;
    const preco = document.getElementById('preco').value;
    const quantidade = document.getElementById('quantidade').value;
    const categoria = document.getElementById('categoria').value;

    if (!nome || !preco || !quantidade || !categoria) {
        alert('Preencha todos os campos!');
        return;
    }

    if (editIndex === null) {
        produtos.push({ nome, preco, quantidade, categoria });
    } else {
        produtos[editIndex] = { nome, preco, quantidade, categoria };
        editIndex = null;
        document.getElementById('btnSalvar').textContent = "Cadastrar";
    }

    localStorage.setItem('produtos', JSON.stringify(produtos));
    exibirProdutos();
    limparCampos();
}

function exibirProdutos(lista = produtos) {
    const tbody = document.getElementById('listaProdutos');
    tbody.innerHTML = '';
    lista.forEach((p, index) => {
        tbody.innerHTML += `<tr>
            <td>${p.nome}</td>
            <td>R$ ${p.preco}</td>
            <td>${p.quantidade}</td>
            <td>${p.categoria}</td>
            <td>
                <button class="btn-edit" onclick="editarProduto(${index})">Editar</button>
                <button class="btn-delete" onclick="excluirProduto(${index})">Excluir</button>
            </td>
        </tr>`;
    });
}

function editarProduto(index) {
    const p = produtos[index];
    document.getElementById('nome').value = p.nome;
    document.getElementById('preco').value = p.preco;
    document.getElementById('quantidade').value = p.quantidade;
    document.getElementById('categoria').value = p.categoria;
    document.getElementById('btnSalvar').textContent = "Salvar Alteração";
    editIndex = index;
}

function excluirProduto(index) {
    if (confirm("Deseja realmente excluir este produto?")) {
        produtos.splice(index, 1);
        localStorage.setItem('produtos', JSON.stringify(produtos));
        exibirProdutos();
    }
}

function limparCampos() {
    document.getElementById('nome').value = '';
    document.getElementById('preco').value = '';
    document.getElementById('quantidade').value = '';
    document.getElementById('categoria').value = '';
}

function filtrarProdutos() {
    const termo = document.getElementById('pesquisa').value.toLowerCase();
    const filtrados = produtos.filter(p => 
        p.nome.toLowerCase().includes(termo) || 
        p.categoria.toLowerCase().includes(termo)
    );
    exibirProdutos(filtrados);
}

exibirProdutos();
