Slimbox.scanPage = function() {
  $$(document.links).filter(function(el) {
    return el.rel && el.rel.test(/^lightbox/i);
  }).slimbox({}, null, function(el) {
    return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
  });
};
window.addEvent("domready", Slimbox.scanPage);