document.addEventListener("DOMContentLoaded",()=>{
  let myList=JSON.parse(localStorage.getItem("myList")||"[]");
  renderMyList();

  document.querySelectorAll(".add-btn").forEach(btn=>{
    const poster=btn.parentElement;
    const iframe=poster.querySelector("iframe");
    const title=poster.querySelector(".video-title").innerText;
    const src=iframe.src;
    if(myList.find(v=>v.src===src)){
      btn.textContent="✔ My List";
      btn.classList.add("added");
    }
    btn.addEventListener("click",()=>{
      const idx=myList.findIndex(v=>v.src===src);
      if(idx===-1){
        myList.push({title,src});
        btn.textContent="✔ My List";
        btn.classList.add("added");
        showToast(title+" added");
      } else {
        myList.splice(idx,1);
        btn.textContent="+ My List";
        btn.classList.remove("added");
        showToast(title+" removed");
      }
      localStorage.setItem("myList",JSON.stringify(myList));
      renderMyList();
    });
  });

  function renderMyList(){
    const row=document.getElementById("myListRow");
    if(!row) return;
    row.innerHTML="";
    myList.forEach(item=>{
      const d=document.createElement("div");
      d.className="poster";
      d.innerHTML=`
        <iframe src="${item.src}" frameborder="0" allowfullscreen></iframe>
        <p class="video-title">${item.title}</p>
        <button class="add-btn added">✔ My List</button>`;
      row.appendChild(d);
      d.querySelector(".add-btn").addEventListener("click",()=>{
        myList=myList.filter(v=>v.src!==item.src);
        localStorage.setItem("myList",JSON.stringify(myList));
        renderMyList();
        showToast(item.title+" removed");
      });
    });
  }

  function showToast(msg){
    let t=document.createElement("div");
    t.className="toast";
    t.textContent=msg;
    document.body.appendChild(t);
    setTimeout(()=>t.classList.add("show"),50);
    setTimeout(()=>{
      t.classList.remove("show");
      setTimeout(()=>t.remove(),300);
    },2500);
  }
});
// Handle "Add to My List"
document.addEventListener("DOMContentLoaded", () => {
  const addBtns = document.querySelectorAll(".add-btn");
  addBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      const poster = btn.parentElement;
      const iframe = poster.querySelector("iframe").src;
      const title = poster.querySelector(".video-title").innerText;

      let myList = JSON.parse(localStorage.getItem("myList")) || [];
      // Avoid duplicates
      if (!myList.find(item => item.src === iframe)) {
        myList.push({ src: iframe, title: title });
        localStorage.setItem("myList", JSON.stringify(myList));
        alert(`${title} added to My List!`);
      } else {
        alert(`${title} is already in My List!`);
      }
    });
  });

  // Load My List page
  const myListContainer = document.getElementById("myListContainer");
  if (myListContainer) {
    let myList = JSON.parse(localStorage.getItem("myList")) || [];
    if (myList.length === 0) {
      myListContainer.innerHTML = "<p style='color:white;'>No items in My List yet.</p>";
    } else {
      myList.forEach(item => {
        const div = document.createElement("div");
        div.classList.add("poster");
        div.innerHTML = `
          <iframe src="${item.src}" frameborder="0" allowfullscreen></iframe>
          <p class="video-title">${item.title}</p>
          <button class="remove-btn">Remove</button>
        `;
        myListContainer.appendChild(div);

        // Remove button
        div.querySelector(".remove-btn").addEventListener("click", () => {
          let updated = myList.filter(x => x.src !== item.src);
          localStorage.setItem("myList", JSON.stringify(updated));
          div.remove();
        });
      });
    }
  }
});
