

function toggle()
{
  var toggler = document.getElementsByClassName("box");
  var i;
   for (i = 0; i < toggler.length; i++) {
  toggler[i].addEventListener("click", function() {
  this.parentElement.querySelector(".nested").classList.toggle("active");
  this.classList.toggle("caret-down");
 })
}
var toggler = document.getElementsByClassName("topbox");
var i;
for (i = 0; i < toggler.length; i++) {
  toggler[i].classList.toggle("active");
}

}
