const tabs = document.querySelectorAll(".tab");

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      
      // remove active from all
      tabs.forEach(t => t.classList.remove("active"));
      
      // add active to clicked one
      tab.classList.add("active");
    });
  });
