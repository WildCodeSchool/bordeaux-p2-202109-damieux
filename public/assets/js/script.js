    let id = 0;
    function addInput_text()
    {
        id++;
        let container = document.getElementById("container");
        let input = document.createElement("input");
        let paragraph = document.createElement('p');
        input.setAttribute("name", "propose[]");
        input.setAttribute("type", "text");
        input.setAttribute("id", id);
        paragraph.appendChild(input);
        container.appendChild(paragraph);
    }

