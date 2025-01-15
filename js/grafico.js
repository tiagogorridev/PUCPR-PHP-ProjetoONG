const formatarMoeda = (valor) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
};

async function buscarDados(dataInicio, dataFim) {
    try {
        const url = `../../grafico.php?data_inicio=${dataInicio}&data_fim=${dataFim}`;
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        
        const dados = await response.json();
        return dados;
        
    } catch (error) {
        console.error("Erro ao buscar dados:", error);
        throw error;
    }
}

async function atualizarGrafico() {
    try {
        const dataInicio = document.getElementById('data_inicio').value || 
            new Date(new Date().getFullYear(), 0, 1).toISOString().slice(0, 10);
        const dataFim = document.getElementById('data_fim').value || 
            new Date().toISOString().slice(0, 10);

        const dados = await buscarDados(dataInicio, dataFim);

        if (!dados || dados.length === 0) {
            throw new Error('Nenhum dado encontrado para o período selecionado');
        }

        const nomes = dados.map(ong => ong.nome);
        const valores = dados.map(ong => ong.valor_total);
        const cores = dados.map((_, index) => 
            `hsl(${(index * 360 / dados.length)}, 70%, 50%)`
        );

        const ctx = document.getElementById('meuGrafico').getContext('2d');

        if (window.meuGrafico instanceof Chart) {
            window.meuGrafico.destroy();
        }

        window.meuGrafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nomes,
                datasets: [{
                    label: 'Valor Total Recebido',
                    data: valores,
                    backgroundColor: cores,
                    borderColor: cores,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Doações por ONG no Período',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                return formatarMoeda(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'ONGs'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Valor Total'
                        },
                        ticks: {
                            callback: (value) => formatarMoeda(value)
                        }
                    }
                }
            }
        });

    } catch (error) {
        console.error('Erro ao atualizar o gráfico:', error);
        alert('Erro ao carregar o gráfico. Por favor, tente novamente.');
    }
}

document.addEventListener('DOMContentLoaded', atualizarGrafico);

document.getElementById('data_inicio').addEventListener('change', atualizarGrafico);
document.getElementById('data_fim').addEventListener('change', atualizarGrafico);