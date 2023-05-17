// Variables

    const 
        
        // Onglet variables
            ongletBtn = document.querySelectorAll('.onglet'),
            ongletLayout = document.querySelectorAll('.onglet-layout'),

        // Dropzone variables
            dropArea = document.querySelector(".dropzone"),
            dragText = document.querySelector(".dropzone-output"),
            content = document.querySelector(".dropzone-content"),
            input = dropArea.querySelector("input"),
            button = document.querySelector(".dropzone-btn"),
            submit = document.querySelector(".dropzone-btn-submit"),
            option = document.querySelector(".dropzone-option"),
            error = document.querySelector(".error"),
            lod = document.querySelector(".lod-cursor"),
            link = document.querySelector(".dowl-link"),
            cancel1 = document.querySelector(".dropzone-btn-cancel"),
            cancel2 = document.querySelectorAll(".onglet-btn-cancel"),
            logs = document.querySelector(".console");

    let 
        // Onglet variables
            index = 0,
            s1 = new Audio("sources/media/song/s1.mp3"),
            s2 = new Audio("sources/media/song/s2.mp3"),

        // Dropzone variables
            file,
            fileName,
            fileExtension;










// Fonctions

    // Onglet switch system
        // ongletBtn.forEach(function(onglet){
        //     onglet.addEventListener('click', () => {
        //         ongletSelector(onglet.getAttribute('data-onglet'));
        //     })
        // })

        // function ongletSelector(index) {
        //     ongletBtn.forEach(function(onglet) {
        //         if(onglet.classList.contains('a-onglet')){
        //             return;
        //         } else {
        //             onglet.classList.add('a-onglet');
        //         }

        //         for(i = 0; i < ongletBtn.length; i++) {
        //             if(ongletBtn[i].getAttribute('data-onglet') !== index){
        //                 ongletBtn[i].classList.remove('a-onglet');
        //             }
        //         }

        //         for(j = 0; j < ongletLayout.length; j++) {
        //             if(ongletLayout[j].getAttribute('data-onglet') == index) {
        //                 ongletLayout[j].classList.add('a-onglet-layout');
        //             } else {
        //                 ongletLayout[j].classList.remove('a-onglet-layout');
        //             }
        //         }

        //         if(index == 2) {
        //             s2.play();
        //         } else if (index == 4) {
        //             s1.play();
        //         }
        //     });
        // }

        function ongletSelector (index) {
            ongletBtn.forEach(function(onglet) {
                if(index == 1){
                    for(i = 0; i < 3; i++) {
                        ongletBtn[i].classList.remove('a-onglet');
                    }

                    ongletBtn[0].classList.add('a-onglet');
                } else if(index == 2 || index == 3){
                    for(i = 0; i < 3; i++) {
                        ongletBtn[i].classList.remove('a-onglet');
                    }

                    ongletBtn[1].classList.add('a-onglet');
                } else if(index == 4){
                    for(i = 0; i < 3; i++) {
                        ongletBtn[i].classList.remove('a-onglet');
                    }

                    ongletBtn[2].classList.add('a-onglet');
                } else {
                    console.log("Error in onglet index value");
                }

                for(j = 0; j < ongletLayout.length; j++) {
                    if(ongletLayout[j].getAttribute('data-onglet') == index) {
                        ongletLayout[j].classList.add('a-onglet-layout');
                    } else {
                        ongletLayout[j].classList.remove('a-onglet-layout');
                    }
                }

                if(index == 2) {
                    s2.play();
                } else if (index == 4) {
                    s1.play();
                }
            });
        }


    // Dropzone system
        button.addEventListener("click", (event) => {
            event.preventDefault();
            event.stopPropagation();
            
            input.click();
        })

        input.addEventListener("change", () => {
            file = input.files[0];
            fileName = input.files[0].name;

            dropArea.classList.add("a-dropzone");
            content.classList.add("a-dropzone-content");
            submit.classList.add("a-dropzone-btn-submit");
            option.classList.add("a-dropzone-option");
            cancel1.classList.add("a-dropzone-btn-cancel");
            dragText.classList.add("a-dropzone-output");

            dragText.textContent = fileName;


            console.log(file);
        })

        dropArea.addEventListener("drop", (event) => {
            event.preventDefault(); 

            file = event.dataTransfer.files[0];
            fileName = event.dataTransfer.files[0].name;

            content.classList.add("a-dropzone-content");
            submit.classList.add("a-dropzone-btn-submit");
            option.classList.add("a-dropzone-option");
            cancel1.classList.add("a-dropzone-btn-cancel");
            dragText.classList.add("a-dropzone-output");

            dragText.textContent = fileName;


            console.log(file);
        })

        dropArea.addEventListener("dragover", (event) => {
            event.preventDefault();

            dropArea.classList.add("a-dropzone");
        })

        dropArea.addEventListener("dragleave", () => {
            dropArea.classList.remove("a-dropzone");
        });

        cancel1.addEventListener("click", (event) => {
            event.preventDefault();
            event.stopPropagation();

            dropArea.classList.remove("a-dropzone");
            content.classList.remove("a-dropzone-content");
            submit.classList.remove("a-dropzone-btn-submit");
            option.classList.remove("a-dropzone-option");
            cancel1.classList.remove("a-dropzone-btn-cancel");
            dragText.classList.remove("a-dropzone-output");
        })

        cancel2[0].addEventListener("click", () => {
            location.reload();
        })

        cancel2[1].addEventListener("click", () => {
            location.reload();
        })

        



    // APP system
        submit.addEventListener("click", (event) => {
            event.preventDefault();

            // Fonction qui sera exécutée si une convertion PDF a été demandée.
            // Cette fonction fetch la progression de la convertion LaTeX en PDF
            async function fetchProgress(id) {
                // Fonction pour attendre une seconde vu que sleep() n'existe pas en Javascript...
                function delay(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }

                // Formdata qui contient le type de requête et l'ID de convertion
                let form = new FormData();
                form.append('type', 'pdfcheck');
                form.append('id', id);

                let end = false;
                let link = "";

                // Boucle pour récupérer l'avancée de la conversion
                while (true) {
                    // Si on a eu un "SUCCESS" dans la réponse de l'API, casser la boucle
                    if (end) break;

                    // On attend une seconde
                    await delay(1000);
                    
                    // On lance la requête vers l'API
                    fetch('app/', { method: "POST", body: form})
                        .then(response => {
                            if (!response.ok) {

                                ongletSelector(2);
                                
                                error.textContent = "Error while doing the request.";
                                throw new Error("Error while doing the request");
    
                            }

                            // On interprète les données comme du JSON
                            return response.json();
                        })
                        .then(json => {
                            
                            console.log(JSON.stringify(json));

                            // Si un erreur s'est produite
                            if (json.status === "ERROR") {
                                
                                ongletSelector(2);

                                error.textContent = "An error happened while trying to convert the file.";
                                logs.textContent = "ERR : " + json.message;
                                throw new Error("TeX convertion passed, but not PDF process");
                            }

                            // Si il y a un "SUCCESS", terminer la boucle
                            if (json.status === "SUCCESS") {
                                end = true;
                                document.querySelector("#pdfdownload").href = json.content.links.pdf;
                                ongletSelector(4);
                            }
                            
                            // Inscrire le pourcetage de l'avancée de la convertion
                            lod.style.width = Math.trunc(json.content.progress) + "%";
                        }
                    );
                }

            }

            // Récupération du nom et de l'extension du fichier
            fileNameNoExt = fileName.split('.').slice(0, -1).join('.');
            fileExtension = fileName.split('.').pop();
            

            // Valeures de l'utilisateur
            let 
                counts = document.querySelector("#ctn").value,
                pdf = document.querySelector("#pdf").checked,
                nonags = document.querySelector("#nag").checked;

            // Si l'extension du fichier n'est pas ".pgn"
            if (fileExtension !== "pgn" ) {
                ongletSelector(2);
                
                error.textContent = "File extension is not valid.";
                throw new Error("File extension is not valid.");
            }

            // Initiation du formdata, contient le fichier, les valeurs de l'utilisateur et le type de requête
            let 
                formdata = new FormData();
                
                formdata.append('dropzone-file', file);
                formdata.append('pdf', pdf);
                formdata.append('nonags', nonags);
                formdata.append('counts', counts);
                formdata.append('type', 'convert');

            // On initie la requête à l'API
            fetch("app/", { method: 'POST', body: formdata })
                .then(response => {
                        // Si il y a eu une erreur lors de la requête
                        if (!response.ok) {
                            ongletSelector(2);

                            error.textContent = "Error while doing the request.";
                            throw new Error("Error while doing the request");
                        }

                        // On interprète la réponse en JSON
                        return response.json();
                    }
                )
                .then(json => {

                        // Si l'API a retourné une erreur
                        if (json.status === "ERROR") {
                            ongletSelector(2);

                            error.textContent = "An error happened while trying to convert the file.";
                            logs.textContent = "ERR : " + json.message;
                            throw new Error("Data sent but something happened server-side");
                        }

                        // Si le client n'as pas choisi de faire la convertion en PDF, rediriger vers la page de téléchargement
                        if (json.content.links.pdf == false) {
                            
                            
                            document.querySelector("#texfilename").textContent = fileNameNoExt + ".tex";
                            document.querySelector("#texdownload").href = json.content.links.tex;
                            document.querySelector(".pdfsection").classList.remove("a-pdfsection");
                            
                            // Onglet téléchargement
                            ongletSelector(4);

                            return console.log('File converted.');
                        }
                        
                        // Onglet d'attente
                        ongletSelector(3);
                    
                        // On commence à remplacer les élements de la page finale
                        document.querySelector("#texfilename").textContent = fileNameNoExt + ".tex";
                        document.querySelector("#texdownload").href = json.content.links.tex;
                        document.querySelector("#pdffilename").textContent = fileNameNoExt + ".pdf";
                        document.querySelector(".pdfsection").classList.add("a-pdfsection");
    
                        // On continue le script dans une fonction pour check l'avancée de la convertion en PDF
                        fetchProgress(json.content.links.pdf);

                        return console.log('Waiting for PDF');
                    }
                );

        })