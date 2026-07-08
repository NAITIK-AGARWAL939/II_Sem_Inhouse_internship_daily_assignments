
const students = [
  {
    name: "Aarav Sharma",
    roll: "101",
    branch: "electrical",
    email: "aarav.sharma@school.com",
    cgpa: "9.1",
    photo: "https://i.pravatar.cc/150?img=12"
  },
  {
    name: "narpat gahlot",
    roll: "102",
    branch: "computer science",
    email: "narpat.gahlot@school.com",
    cgpa: "8.7",
    photo: "https://i.pravatar.cc/150?img=47"
  },
  {
    name: "parth saraswat",
    roll: "103",
    branch: "mechanical",
    email: "parth.saraswat@school.com",
    cgpa: "8.3",
    photo: "https://i.pravatar.cc/150?img=33"
  },
  {
    name: "tanuj mishra",
    roll: "104",
    branch: "civil",
    email: "tanuj.mishra@school.com",
    cgpa: "8.1",
    photo: "https://i.pravatar.cc/150?img=25"
  },
  {
    name: "prashant jain",
    roll: "105",
    branch: "electrical",
    email: "prashant.jain@school.com",
    cgpa: "8.5",
    photo: "https://i.pravatar.cc/150?img=67"

  }
];

const container = document.getElementById('studentContainer');
const noResult = document.getElementById('noResult');

function renderStudents(list) {
  container.innerHTML = "";
  if (list.length === 0) {
    noResult.style.display = "block";
    return;
  }
  noResult.style.display = "none";

  list.forEach(student => {
    const card = document.createElement('div');
    card.className = 'card';
    card.innerHTML = `
      <img src="${student.photo}" alt="${student.name}">
      <h3>${student.name}</h3>
      <p class="roll">Roll No: ${student.roll}</p>
      <p>Branch: ${student.branch}</p>
      <p>CGPA: ${student.cgpa}</p>
      <p>${student.email}</p>
    `;
    container.appendChild(card);
  });
}

function searchStudent() {
  const query = document.getElementById('searchInput').value.trim().toLowerCase();
  if (query === "") {
    renderStudents(students);
    return;
  }
  const filtered = students.filter(s =>
    s.name.toLowerCase().includes(query) || s.roll.includes(query)
  );
  renderStudents(filtered);
}

// live search as user types
document.getElementById('searchInput').addEventListener('input', searchStudent);

// show all students on page load
renderStudents(students);
