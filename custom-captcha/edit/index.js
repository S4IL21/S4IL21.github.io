document.getElementById("mkc").addEventListener("click", () => {
    const captcha = btoa(JSON.stringify({
        cct: document.getElementById("cct").value,
        title: document.getElementById("title").value,
        desc: document.getElementById("desc").value,
        text: document.getElementById("txt").value,
        ctext: document.getElementById("ctxt").value,
        ccolor: document.getElementById("cctxt").value,
        wtext: document.getElementById("wtxt").value,
        wcolor: document.getElementById("wctxt").value,
    })) + "." + btoa(document.getElementById("type").value);
    document.getElementById("mkc").innerText = "Sucess!";
    document.getElementById("ycl").href = "../?c=" + encodeURIComponent(captcha);
    document.getElementById("ycl").style.display = "block"
    document.getElementById("mkc").disabled = true;
})