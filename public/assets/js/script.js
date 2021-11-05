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
    input.setAttribute("class", 'form-control border rounded mb-3');
    paragraph.appendChild(input);
    container.appendChild(paragraph);
}
