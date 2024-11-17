// Carrinho de compras como uma lista de objetos
const cart = [];
const cartContainer = document.getElementById('cart-items');
const cartTotal = document.getElementById('cart-total');
const checkoutButton = document.getElementById('checkout-button');

// Atualiza o carrinho na interface
function updateCart() {
    cartContainer.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        cartContainer.innerHTML = '<p>O carrinho está vazio.</p>';
        checkoutButton.style.display = 'none';
        cartTotal.textContent = '0,00';
        return;
    }

    cart.forEach((item, index) => {
        const itemDiv = document.createElement('div');
        itemDiv.innerHTML = `
            <p>
                ${item.nome} - R$ ${item.preco.toFixed(2)} x ${item.quantidade}
                <button data-index="${index}" class="remove-from-cart">Remover</button>
            </p>
        `;
        cartContainer.appendChild(itemDiv);
        total += item.preco * item.quantidade;
    });

    cartTotal.textContent = total.toFixed(2).replace('.', ',');
    checkoutButton.style.display = 'block';
}

// Adiciona um item ao carrinho
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.dataset.id;
        const nome = button.dataset.nome;
        const preco = parseFloat(button.dataset.preco);
        const max = parseInt(button.dataset.max);
        const quantidadeInput = document.getElementById(`quantidade_${id}`);
        const quantidade = parseInt(quantidadeInput.value);

        if (!quantidade || quantidade <= 0 || quantidade > max) {
            alert('Por favor, insira uma quantidade válida!');
            return;
        }

        const existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.quantidade + quantidade > max) {
                alert('Quantidade excede o estoque disponível!');
                return;
            }
            existing.quantidade += quantidade;
        } else {
            cart.push({ id, nome, preco, quantidade });
        }

        updateCart();
        quantidadeInput.value = ''; // Limpa o campo de quantidade
    });
});

// Remove um item do carrinho
cartContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-from-cart')) {
        const index = parseInt(e.target.dataset.index);
        cart.splice(index, 1);
        updateCart();
    }
});

// Finaliza o pedido
checkoutButton.addEventListener('click', () => {
    if (cart.length === 0) {
        alert('O carrinho está vazio!');
        return;
    }

    // Exibe o carrinho no console (ou envia para o backend)
    const pedido = JSON.stringify(cart);
    console.log('Pedido enviado:', pedido); // Substituir por requisição AJAX

    alert('Pedido finalizado com sucesso!');
    cart.length = 0; // Limpa o carrinho
    updateCart();
});

// Inicializa o estado do carrinho
updateCart();
