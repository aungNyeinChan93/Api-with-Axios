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
            <div class="col-8">
                <h5 class="text-info text-center my-3">Table</h5>
                <table class="table table-border table-hover  table-primary  text-start">
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

    </div>
</body>


<script>
    let form = document.getElementById("tbody");
    let listUrl = "posts";
    // list data
    axios.get(listUrl)
        .then((res) => {
            res.data.forEach((item) => {
                form.innerHTML +=
                    `
                    <tr>
                        <td> ${item.id} </td>
                        <td> ${item.title} </td>
                        <td> ${item.description.slice(0,50)} </td>
                        <td>
                            <a href="" class="btn btn-sm btn-success">Edit</a>
                            <a href="" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    `
            })
        }).catch((err) => {
            // console.log(err);
            // console.log(err.message);
            // console.log(err.response.statusText);
            if (err.response.status == 404 || err.response.status !== 200) {
                console.log(`This ${err.config.url} url is ${err.response.status} ${err.response.statusText} !!!`);
            }
        })

    // create post
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
            } else if (res.data.msg !== "Create sucess!!") {
                if (title.value == "") {
                    document.getElementById("error_title").innerHTML =
                        `<span class="text-danger ">${res.data.msg.title}</span>`;
                }
                if(res.data.msg.description ==" Description field name is required!"){
                    document.getElementById("error_description").innerHTML =
                    `<span class="text-danger ">${res.data.msg.description}</span>`;
                }
            }
        }).catch((err) => {
            console.log(err.message);
        });

    }
</script>

{{-- bootstrap CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Axios CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</html>
