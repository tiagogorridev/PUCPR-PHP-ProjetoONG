    async function buscarEnderecoPorCEP(cep) {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);

        if (!response.ok) {
            console.error('Erro ao buscar o CEP');
            return;
        }

        const dados = await response.json();

        if (dados.erro) {
            alert('CEP inválido!');
            return;
        }

        document.getElementById('bairro').value = dados.bairro;
        document.getElementById('cidade').value = dados.localidade;
        document.getElementById('estado').value = dados.uf;
    }

    document.getElementById('cep').addEventListener('input', function () {
        const cep = this.value.replace(/\D/g, '');

        if (cep.length === 8) {
            buscarEnderecoPorCEP(cep);
        }
    });

    document.querySelector('form').addEventListener('submit', function (event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (password !== confirmPassword) {
            alert('As senhas não coincidem. Por favor, tente novamente.');
            event.preventDefault(); 
        }

        const cpf = document.getElementById('cpf').value.replace(/\D/g, ''); 
        if (cpf.length !== 11) {
            alert('CPF inválido! Por favor, insira um CPF com 11 dígitos.');
            event.preventDefault();
            return;
        }

        const telefone = document.getElementById('phone').value.replace(/\D/g, '');
        if (telefone.length !== 11) {
            alert('Telefone inválido! Por favor, insira um número de telefone válido com 11 dígitos.');
            event.preventDefault();
            return;
        }

        const cepInput = document.getElementById('cep').value.replace(/\D/g, '');
        if (cepInput.length !== 8) {
            alert('CEP inválido! Por favor, insira um CEP válido com 8 dígitos.');
            event.preventDefault();
            return;
        }
    });

    function validarSomenteNumeros(event) {
        const campo = event.target;
        campo.value = campo.value.replace(/\D/g, ''); 
    }

    function validarNome(event) {
        const campo = event.target;
        campo.value = campo.value.replace(/[^a-zA-Z\s]/g, ''); 
    }


    document.getElementById('cpf').addEventListener('input', validarSomenteNumeros);
    document.getElementById('phone').addEventListener('input', validarSomenteNumeros);
    document.getElementById('cep').addEventListener('input', validarSomenteNumeros);
    document.getElementById('name').addEventListener('input', validarNome);
    document.getElementById('numero').addEventListener('input', validarSomenteNumeros);

    
