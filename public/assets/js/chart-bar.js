const ctx = document.getElementById('myChart');
const answerComma = ctx.dataset.answers;
const answers = answerComma.split(',');
const countChoiceComma = ctx.dataset.count;
const countChoices = countChoiceComma.split(',');

const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: answers,
        datasets: [{
            label: 'Nombre de Votes',
            borderRadius: 5,
            data: countChoices,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                display: true,
                grid : {
                    display: false
                },
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                grid : {
                    display: false
                }
            }
        },
    }
});
