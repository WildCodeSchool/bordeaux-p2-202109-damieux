const votes = document.getElementsByClassName('vote');
console.log(votes);
for (const vote of votes) {
    vote.addEventListener('click', function(){
        vote.classList.toggle('clicked')
    })
}