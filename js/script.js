let cart = JSON.parse(localStorage.getItem("cart")) || [];

document.querySelectorAll(".add-cart").forEach(button => {
  button.addEventListener("click", () => {
    const id = button.dataset.id;
    const title = button.dataset.title;
    const price = parseInt(button.dataset.price);

    const existing = cart.find(item => item.id === id);
    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push({ id, title, price, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    alert(`${title} added to cart!`);
  });
});
