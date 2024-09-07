<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>API CRUD</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div id="update_success"></div>
            <div class="col-8">
                <h5 class="text-info text-center my-3">Table</h5>
                <table class="table table-bordered table-hover  table-primary  text-start">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <h5 class="text-info text-center my-3">Form</h5>
                <div id="create_success"></div>
                <form name="postFrom">
                    <input type="text" name="title" class="form-control my-3">
                    <div id="error_title"></div>
                    <textarea name="description" class="form-control my-3" rows="10"> </textarea>
                    <div id="error_description"></div>
                    <input class="btn btn-sm btn-success" type="submit" value="Submit">
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Post Update</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form name="updateForm" id="update">

                            <input type="hidden" name="id" id="postId" value=`${item.id}`>

                            <input id="title" type="text" name="title" class="form-control my-3">

                            <textarea id="description" name="description" class="form-control my-3" rows="10"> </textarea>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</body>
{{-- bootstrap CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Axios CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    //
    let titleList = document.getElementsByClassName("titleList");
    let descList = document.getElementsByClassName("descList");
    let btnList = document.getElementsByClassName("btnList");
    let idList = document.getElementsByClassName("idList");
    // console.log(titleList);

    let oldTitle;
    let oldDesc;


    // list post
    let listUrl = "/posts";
    axios.get(listUrl)
        .then((res) => {
            res.data.forEach((item) => {
                display(item);
            })
        }).catch((err) => {
            if (err.response.status == 404 || err.response.status !== 200) {
                console.log(`This ${err.config.url} url is ${err.response.status} ${err.response.statusText} !!!`);
            }
        })

    // create post ->fetch store()
    let postForm = document.forms["postFrom"];
    let title = postForm["title"];
    let description = postForm["description"];
    let postUrl = "/posts";
    postForm.onsubmit = function(e) {
        e.preventDefault();
        axios.post(postUrl, {
            title: title.value,
            description: description.value,
        }).then((res) => {
            if (res.data.msg == "Create sucess!!") {
                document.getElementById("create_success").innerHTML =
                    `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>${res.data.msg}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                `
                // add new post
                display(res.data[0]);
                //form.value = "";
                postForm.reset();
            } else if (res.data.msg !== "Create sucess!!") {
                if (title.value == "") {
                    document.getElementById("error_title").innerHTML =
                        `<span class="text-danger ">${res.data.msg.title}</span>`;
                } else {
                    document.getElementById("error_title").innerHTML = "";
                }
                document.getElementById("error_description").innerHTML = res.data.msg ==
                    " Description field name is required!" ?
                    `<span class="text-danger ">${res.data.msg.description}</span>` : "";
            }
        }).catch((err) => {
            console.log(err.message);
        });
    }

    // edit post ->fetch show()
    function editBtn(id) {
        axios.get(`/posts/${id}`).then((res) => {
            oldTitle = res.data.title;
            oldDesc = res.data.description;
            document.getElementById("title").value = res.data.title;
            document.getElementById("description").value = res.data.description;
            document.getElementById("postId").value = res.data.id;
        }).catch((err) => {
            console.log(err);
        })
    }

    // update post ->fetch update()
    let updateForm = document.forms["updateForm"];
    let updateTitle = updateForm["title"];
    let updateDescription = updateForm["description"];
    let postId = updateForm["id"];
    updateForm.onsubmit = function(e) {
        e.preventDefault();
        // console.log(updateTitle.value,updateDescription.value,postId.value);
        axios.put(`/posts/${postId.value}`, {
            title: updateTitle.value,
            description: updateDescription.value,
        }).then((res) => {
            // console.log(oldTitle);
            // console.log(titleList[i].innerHTML);
            if (res.status == 200) {
                document.getElementById("update_success").innerHTML =
                    `<small class="h5 text-success">${res.data.msg}</small>`;
                for (let i = 0; i < titleList.length; i++) {
                    if (titleList[i].innerHTML == oldTitle) {
                        titleList[i].innerHTML = updateTitle.value;
                        descList[i].innerHTML = updateDescription.value;
                    }
                }
            }
        }).catch(err => {
            console.log(err.message);
        })

    }

    // delete post ->fetch destroy()
    function deleteBtn(id) {
        // console.log(id);
        if(confirm("are you sure!")){
            axios.delete(`posts/${id}`)
            .then(res => {
                // console.log(titleList[0])
                for(let i=0 ; i < titleList.length ; i++){
                    if(descList[i].innerHTML == res.data[0].description){
                        titleList[i].style.display = "none";
                        descList[i].style.display = "none";
                        btnList[i].style.display = "none";
                        idList[i].style.display = "none";
                    }
                }
            })
            .catch(err => console.log(err))
        }
    }

    // helper function
    function display(data) {
        document.getElementById("tbody").innerHTML +=
            `
                   <tr>
                        <td class='idList'> ${data.id} </td>
                        <td class="titleList"> ${data.title} </td>
                        <td class="descList"> ${data.description.slice(0,50)} </td>
                        <td>
                            <button class="btn btn-sm btn-success btnList" data-bs-toggle="modal" data-bs-target="#postModal"
                            onclick="editBtn(${data.id})">Edit</button>
                            <button onclick="deleteBtn(${data.id})" class="btn btn-sm btn-danger btnList">Delete</button>
                        </td>
                    </tr>
                `;
    }
</script>



</html>
