require("./bootstrap");

Echo.channel("urls")
    .listen(".download.started", e => {})
    .listen(".download.progress", e => {
        console.log("progress", e);
        $(`#url-${e.url.id}`)
            .attr(
                "class",
                `url-result url-status-${e.url.status.replace(" ", "_")}`
            )
            .find("span.status-bar")
            .css({ width: `${e.url.progress}%` });
    })
    .listen(".download.error", e => {
        console.log("error", e);
        $(`#url-${e.url.id}`).attr(
            "class",
            `url-result url-status-${e.url.status.replace(" ", "_")}`
        );
    })
    .listen(".upload.started", e => {
        $(`#url-${e.url.id}`).attr(
            "class",
            `url-result url-status-${e.url.status.replace(" ", "_")}`
        );
    })
    .listen(".download.done", e => {
        $(`#url-${e.url.id}`).attr(
            "class",
            `url-result url-status-${e.url.status.replace(" ", "_")}`
        );
    });

let busy = false;
const form = document.querySelector("#upload-form");
const text_urls = document.querySelector("#urls");
const btn = document.querySelector("#submit");
const form_status = document.querySelector("#form-status");

form.addEventListener("submit", async e => {
    if (busy) return false;

    e.preventDefault();
    const post_urls = text_urls.value
        .replace(/\r/gi, "")
        .split("\n")
        .map(url => url.trim())
        .filter(url => url.length > 0);
    const action = form.getAttribute("action");
    if (!post_urls.length) return;

    busy = true;
    btn.setAttribute("disabled", "disabled");
    form_status.innerHTML = "Working on it...";
    text_urls.value = "";
    const { data } = await axios.post(action, { urls: post_urls });
    const { urls } = data;
    btn.removeAttribute("disabled");
    form_status.innerHTML = "";
    busy = false;

    const results = document.querySelector("#results");

    urls.forEach(url => {
        results.innerHTML += `<div id="url-${
            url.id
        }" class="url-result url-status-${url.status.replace(" ", "_")}">
            ${url.name} <span class="status-bar" id="url-${url.id}-status"></span><span class="text-status">Uploading to Gdrive</span>
        </div>`;
    });
});
