document.getElementById("mkc").addEventListener("click", () => {
    const captcha = btoa(JSON.stringify({title: document.getElementById("title").value,desc: document.getElementById("desc").value,text: document.getElementById("txt").value,ctext: document.getElementById("ctxt").value,ccolor: document.getElementById("cctxt").value})) + "." + btoa(document.getElementById("type").value);
    document.getElementById("mkc").innerText = "Sucess!";
    document.getElementById("ycl").href = "../?c=" + captcha;
    document.getElementById("ycl").style.display = "block"
    document.getElementById("mkc").removeEventListener("click");
})