function doSelect() {
            var thetree = document.getElementById("listAllSlides");
            var currentRow = thetree.lastChild.childNodes.item(thetree.currentIndex);
            var treecells = currentRow.childNodes;
            for( var i=0; i<treecells.length; i++){
              alert(treecells.item(i).id);
            }
}