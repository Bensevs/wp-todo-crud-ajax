# 📝 WordPress To-Do List Plugin (Full CRUD + AJAX + Bootstrap)

A complete full-stack WordPress plugin that provides a **To-Do List** with:

✅ Full **CRUD** functionality (Create, Read, Update, Delete)  
✅ Custom **REST API Endpoints**  
✅ **jQuery** frontend with **AJAX** communication  
✅ **Bootstrap** styling for responsive UI  
✅ **Nonce security** for safe AJAX requests  
✅ Custom **Database Table** created using `dbDelta`

---

## 🚀 Features

- ➕ Add new tasks
- 📝 Edit and update existing tasks
- 🗑️ Delete tasks
- 🔄 Real-time updates without page refresh (AJAX)
- 🛡️ Secured with WordPress Nonce & REST permissions

_______________________________________________________________________________________________________________________________________________


---

## ⚙️ Installation & Usage

1️⃣ Upload the `plugin/` folder to your `/wp-content/plugins/` directory.  
2️⃣ Activate the plugin from **WordPress Admin > Plugins**.  
3️⃣ Create a WordPress page and load the `mytodo.js` script or use the provided `/demo/index.html` as a frontend.  
4️⃣ Start adding, editing, and deleting tasks.

---

## 🛠 Technologies Used

- WordPress (REST API, dbDelta)
- PHP 8+
- MySQL
- jQuery (AJAX)
- Bootstrap 5 (Responsive UI)

---

## 🧩 Security

✅ **Nonce authentication** for all data-changing REST requests.  
✅ All inputs are sanitized using `sanitize_text_field()`.  

---

## 💡 Possible Improvements

- ✅ Convert frontend to **React** or **Vue**  
- ✅ Add **user-based task ownership**  
- ✅ Add **pagination** or **search**  

---

## 📄 License

MIT License

---

👉 **Demo:** [Add a link if you have one]  
👉 **Author:** [Your Name] | [LinkedIn] | [Portfolio]
