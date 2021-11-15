const votes = document.getElementsByClassName('vote');
for (const vote of votes) {
    vote.addEventListener('click', function(){
        vote.classList.toggle('clicked')
    })
}