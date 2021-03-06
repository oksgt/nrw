let pathNumber = 1;
let allLinks = [];
let treeParamas;

// scg path params
let strokeWidth = '5px';
let strokeColor = '#000000';

function treeMaker(tree, params) {
    let container = document.getElementById(params.id);
    treeParamas = params.treeParams === undefined ? {} : params.treeParams;

    if (params.link_width !== undefined) strokeWidth = params.link_width;
    if (params.link_color !== undefined) strokeColor = params.link_color;

    // reset pathNumber and allLinks globals to allow on-click function to re-call treeMaker()
    pathNumber = 1;
    allLinks = [];

    // svg part
    let svgDiv = document.createElement('div');
    svgDiv.id = 'tree__svg-container';
    container.appendChild(svgDiv);
    let svgContainer = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svgContainer.id = 'tree__svg-container__svg';
    svgDiv.appendChild(svgContainer);

    // html part
    let treeContainer = document.createElement('div');
    treeContainer.id = 'tree__container';
    container.appendChild(treeContainer);
    let card = document.createElement('div');
    card.classList = 'tree__container__step__card';
    card.id = 'tree__container__step__card__first';

    

    treeContainer.appendChild(card);
    // console.log(treeParamas[Object.keys(tree)[0]].img);
    let trad = treeParamas[Object.keys(tree)[0]] !== undefined && treeParamas[Object.keys(tree)[0]].trad !== undefined ? treeParamas[Object.keys(tree)[0]].trad : Object.keys(tree)[0].trad;
    card.innerHTML = '<p  data-step='+treeParamas[Object.keys(tree)[0]].current_step+'  onclick="getTableDetail(' +  Object.keys(tree)[0] + ')" class="tree__container__step__card__p node" id="card_' + Object.keys(tree)[0] + '">' + trad +'<br>'+
    '<img onclick="$("#card_' + Object.keys(tree)[0] + '").click(); " data-id="' +  Object.keys(tree)[0] + '" style=" height:100px; width:200px; margin:auto;" src="'+base_url+'assets/'+treeParamas[Object.keys(tree)[0]].img+'">'+
    '</p>';


    addStyleToCard(treeParamas[Object.keys(tree)[0]], Object.keys(tree)[0]);

    iterate(tree[Object.keys(tree)[0]], true, 'tree__container__step__card__first');

    connectCard();

    let allCards = document.querySelectorAll('.tree__container__step__card__p');
    for (let i = 0; allCards.length > i; i++) {
        allCards[i].addEventListener('click', function (event) {
            params.card_click(event.target);
        });
    }

    window.onresize = function () {
        svgDiv.setAttribute('height', '0');
        svgDiv.setAttribute('width', '0');  
        connectCard();
    };
}

function connectCard() {
    // magic
    let svg = document.getElementById('tree__svg-container__svg');
    for (let i = 0; allLinks.length > i; i++) {
        // console.log(document.getElementById(allLinks[i][0]));
        // console.log(document.getElementById(allLinks[i][1]));
        // console.log(document.getElementById(allLinks[i][2]));
        // console.log("---------");
        connectElements(svg, document.getElementById(allLinks[i][0]), document.getElementById(allLinks[i][1]), document.getElementById(allLinks[i][2]));
    }
}

function iterate(tree, start = false, from = '') {
    let svgContainer = document.getElementById('tree__svg-container__svg');
    let treeContainer = document.createElement('div');
    treeContainer.id = 'from_' + from;
    treeContainer.classList.add('tree__container__branch');
    document.getElementById(from).after(treeContainer);

    for (const key in tree) {
        // console.log('is logger '+treeParamas[key].is_logger);
        let textCard = treeParamas[key] !== undefined && treeParamas[key].trad !== undefined ? treeParamas[key].trad : key;
        
        if(treeParamas[key].is_logger){
            treeContainer.innerHTML += '<div class="tree__container__step"><div class="tree__container__step__card" id="' + key + '">'+
            '<p data-step='+treeParamas[key].current_step+' data-parent-step='+treeParamas[key].parent_step+' id="card_' + key + '" class="tree__container__step__card__p node" onclick="window.open(\''+"http://pdambanyumas.net/mendoan_x/speedometer/cabang_pwt2_spam_sidabowa.php"+'\')">' + 
            'Logger '+ treeParamas[key].kode +'<br>'+
            '<span style="font-size: 1.1em;" class="badge badge-secondary " id="P_' + treeParamas[key].kode + '">' + '' + '</span>&nbsp;<span style="font-size: 1.1em;" class="badge  badge-secondary" id="Q_' + treeParamas[key].kode + '">' + '' + '</span>'+
            '</p>'+ 
            '</div></div>';
        } else {
            treeContainer.innerHTML += '<div class="tree__container__step"><div class="tree__container__step__card" id="' + key + '">'+  
            '<p data-step='+treeParamas[key].current_step+' data-parent-step='+treeParamas[key].parent_step+' onclick="getTableDetail(' + key + ')" id="card_' + key + '" class="tree__container__step__card__p node">' + 
            '<img onclick="$("#card_' + key + '").click(); " data-id="' + key + '" style=" height:100px; width:200px; margin:auto;" src="'+base_url+'assets/'+treeParamas[key].img+'" ><br>'+
            '' + "" +textCard+'<br>'+
            '</p>'+ 
            '</div></div>';
        }
        
        // treeContainer.onclick = function () {
        //     alert(treeParamas[key].trad);
        // };
        addStyleToCard(treeParamas[key], key);
        if ('' !== from && !start) {
            let newpath = document.createElementNS("http://www.w3.org/2000/svg", "path");
            newpath.id = "path" + pathNumber;
            newpath.setAttribute('stroke', "#2199e8");
            newpath.setAttribute('fill', 'none');
            newpath.setAttribute('stroke-width', "10px");
            svgContainer.appendChild(newpath);

            //animated dash path
            let dashpath = document.createElementNS("http://www.w3.org/2000/svg", "path");
            // dashpath.class = "path";
            dashpath.id = "path_" + pathNumber;
            dashpath.setAttribute('class', "path");
            dashpath.setAttribute('stroke-width', 4);
            dashpath.setAttribute('fill', 'none');
            dashpath.setAttribute('stroke', "#FFFFFF");
            dashpath.setAttribute('stroke-dasharray', 10);
            svgContainer.appendChild(dashpath);

            // console.log(key)
            allLinks.push(['path' + pathNumber, from, key]);
            allLinks.push(['path_' + pathNumber, from, key]);
            pathNumber++;
        }
        if (start) {
            // svgContainer.innerHTML = svgContainer.innerHTML + '<path id="path' + pathNumber + '" d="M0 0" stroke="#2199e8" fill="none" stroke-width="5";/>';
            let newpath = document.createElementNS("http://www.w3.org/2000/svg", "path");
            newpath.id = "path" + pathNumber;
            newpath.setAttribute('stroke', "#2199e8");
            newpath.setAttribute('fill', 'none');
            newpath.setAttribute('stroke-width', "10px");
            svgContainer.appendChild(newpath);

            //animated dash path
            let dashpath = document.createElementNS("http://www.w3.org/2000/svg", "path");
            // dashpath.class = "path";
            dashpath.id = "path_" + pathNumber;
            dashpath.setAttribute('class', "path"); 
            dashpath.setAttribute('stroke-width', 4);
            dashpath.setAttribute('fill', 'none');
            dashpath.setAttribute('stroke', "#FFFFFF");
            dashpath.setAttribute('stroke-dasharray', 10);
            svgContainer.appendChild(dashpath);
            // console.log(key)
            allLinks.push(['path' + pathNumber, 'tree__container__step__card__first', key]);
            allLinks.push(['path_' + pathNumber, 'tree__container__step__card__first', key]);
            pathNumber++;
        }

        if (Object.keys(tree[key]).length > 0) {
            iterate(tree[key], false, key);
        }
    }
}

function addStyleToCard(card, key) {
    if (card !== undefined && card.styles !== undefined) {
        let lastCard = document.getElementById('card_' + key);
        for (const cssRules in treeParamas[key].styles) {
            lastCard.style[cssRules] = card.styles[cssRules];
        }
    }
}

function signum(x) {
    return (x < 0)
        ? -1
        : 1;
}

function absolute(x) {
    return (x < 0)
        ? -x
        : x;
}

function drawPath(svg, path, startX, startY, endX, endY) {
    // get the path's stroke width (if one wanted to be  really precize, one could use half the stroke size)
    let stroke = parseFloat(path.getAttribute("stroke-width"));
    // check if the svg is big enough to draw the path, if not, set heigh/width
    if (svg.getAttribute("height") < endY) svg.setAttribute("height", endY);
    if (svg.getAttribute("width") < (startX + stroke)) svg.setAttribute("width", (startX + stroke));
    if (svg.getAttribute("width") < (endX + stroke)) svg.setAttribute("width", (endX + stroke));

    let deltaX = (endX - startX) * 0.25;
    let deltaY = (endY - startY) * 0.25;
    // for further calculations which ever is the shortest distance
    let delta = deltaY < absolute(deltaX)
        ? deltaY
        : absolute(deltaX);

    // set sweep-flag (counter/clock-wise)
    // if start element is closer to the left edge,
    // draw the first arc counter-clockwise, and the second one clock-wise
    let arc1 = 0;
    let arc2 = 1;
    if (startX > endX) {
        arc1 = 1;
        arc2 = 0;
    }
    // draw tha pipe-like path
    // 1. move a bit down, 2. arch,  3. move a bit to the right, 4.arch, 5. move down to the end
    path.setAttribute("d", "M" + startX + " " + startY + " V" + (startY + delta) + " A" + delta + " " + delta + " 0 0 " + arc1 + " " + (startX + delta * signum(deltaX)) + " " + (startY + 2 * delta) + " H" + (endX - delta * signum(deltaX)) + " A" + delta + " " + delta + " 0 0 " + arc2 + " " + endX + " " + (startY + 3 * delta) + " V" + endY);
}

function connectElements(svg, path, startElem, endElem) {
    let svgContainer = document.getElementById('tree__svg-container');

    // if first element is lower than the second, swap!
    if (startElem.offsetTop > endElem.offsetTop) {
        let temp = startElem;
        startElem = endElem;
        endElem = temp;
    }

    // get (top, left) corner coordinates of the svg container
    let svgTop = svgContainer.offsetTop;
    let svgLeft = svgContainer.offsetLeft;

    // calculate path's start (x,y)  coords
    // we want the x coordinate to visually result in the element's mid point
    let startX = startElem.offsetLeft + 0.5 * startElem.offsetWidth - svgLeft;    // x = left offset + 0.5*width - svg's left offset
    let startY = startElem.offsetTop + startElem.offsetHeight - svgTop;        // y = top offset + height - svg's top offset

    // calculate path's end (x,y) coords
    let endX = endElem.offsetLeft + 0.5 * endElem.offsetWidth - svgLeft;
    let endY = endElem.offsetTop - svgTop;

    // call function for drawing the path
    // console.log(path)
    drawPath(svg, path, startX, startY, endX, endY);
}
