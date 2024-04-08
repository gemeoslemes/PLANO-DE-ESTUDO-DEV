function showTitle(title) {
    var tooltip = document.createElement("div");
    tooltip.textContent = title;
    tooltip.style.position = "absolute";
    tooltip.style.background = "rgba(0, 0, 0, 0.7)";
    tooltip.style.color = "#fff";
    tooltip.style.padding = "5px";
    tooltip.style.borderRadius = "5px";
    tooltip.style.top = event.clientY + "px";
    tooltip.style.left = event.clientX + "px";
    document.body.appendChild(tooltip);
    event.target.tooltip = tooltip;
}

function hideTitle() {
    if (event.target.tooltip) {
        document.body.removeChild(event.target.tooltip);
        event.target.tooltip = null;
    }
}