function copy_link() {
    var link = document.getElementById("link").innerHTML;
    console.log(link);
    navigator.clipboard.writeText(link)
        .then(() => {
            alert("The link is copied to your clipboard, now you can paste it to your social media story")
        })
        .catch((error) => {
            alert("There was an error copying your link")
        });
};

function save_cache() {
    const email = document.getElementsByName("email").value;
    const password = document.getElementsByName("password").value;

    window.localStorage.setItem("email", email);
    window.localStorage.setItem("password", password);

    // console.log("saved cache");
};