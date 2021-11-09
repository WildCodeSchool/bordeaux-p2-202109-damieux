let id = 0;
function addInput()
{
    id++;
    let container = document.getElementById("container");
    let input = document.createElement("input");
    let paragraph = document.createElement('div');
    input.setAttribute("name", "propose[]");
    input.setAttribute("type", "text");
    input.setAttribute("id", id);
    input.setAttribute("class", 'form-control border-0 border-bottom mb-2');
    paragraph.appendChild(input);
    container.appendChild(paragraph);
    let totalPropose = id + 1;
    if (totalPropose >= 6) {
        const addPropose = document.getElementById('add-propose');
        addPropose.innerHTML = "Vous ne pouvez plus ajouter d'option";
    } 
}