let node = document.getElementById("my-node");

htmlToImage
  .toPng(node)
  .then(function (dataUrl) {
    var img = new Image();
    img.src = dataUrl;
    img.style = "width: 100%;";
    document.body.style = "padding: 30px;";
    document.body.innerHTML = "";
    document.body.appendChild(img);
  })
  .catch(function (error) {
    console.error("oops, something went wrong!", error);
  });
