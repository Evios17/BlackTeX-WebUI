const ongletBtn = document.querySelectorAll('.onglet');
const ongletLayout = document.querySelectorAll('.onglet-layout');
let index1 = 0;

let s1 = new Audio("sources/media/song/s1.mp3");
let s2 = new Audio("sources/media/song/s2.mp3");


ongletBtn.forEach(onglet => {
    onglet.addEventListener('click', () => {
        if(onglet.classList.contains('a-onglet')){
            return;
        }else{
            onglet.classList.add('a-onglet');
        }

        index1 = onglet.getAttribute('data-onglet');
        console.log(index1);

        for(i = 0; i < ongletBtn.length; i++) {
            if(ongletBtn[i].getAttribute('data-onglet') != index1){
                ongletBtn[i].classList.remove('a-onglet');
            }
        }

        for(j = 0; j < ongletLayout.length; j++) {
            if(ongletLayout[j].getAttribute('data-onglet') == index1){
                ongletLayout[j].classList.add('a-onglet-layout');
            }else{
                ongletLayout[j].classList.remove('a-onglet-layout');
            }
        }

        if(index1 == 2){
            s2.play();
        } else if (index1 == 4){
            s1.play();
        }
    })
})