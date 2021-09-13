/**
 * Generic method to add TARGET blank and correct REL to 
 * all external links
 * 
 * @author Andrew Killen
 * @version 1.0
 * 
 */
const fixExternalLinks = {
    /**
     * check if a link is external 
     *  
     * @return bool
     */ 
    isExternalLink : function(linkElm){
      return (linkElm.host !== window.location.host);
    },
    
    init : function(){
      let links = document.getElementsByTagName('a');
      links.map((elm)=>{
          if (fixExternalLinks.isExternalLink(elm)) {
            elm.setAttribute("target","_blank");
            elm.setAttribute("rel","noopener noreferrer");
          } 
      });  
        
      }
    
};
fixExternalLinks.init();
