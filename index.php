<?php
include_once 'header.php';
include_once 'includes/dbh.inc.php';
include_once 'includes/functions.inc.php';
include_once 'get_courses.php';
?>

<section class="index-intro">
    <?php
    if (isset($_SESSION["useruid"])) {
        $userId = $_SESSION["userid"];
        echo "<p> Hello there " . $_SESSION["useruid"] . "</p>";
        $courses = getCourses($conn, $userId);
    ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Course Creator</title>
<style>
    #canvas {
        border: 1px solid black;
        cursor: crosshair;
    }
</style>
</head>
<body>
<button id="createCourseBtn">Create Course</button>
<button id="moveCoursesBtn">Move Courses</button>
<button id="deleteCourseBtn">Delete Course</button>
<canvas id="canvas" width="400" height="300"></canvas>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
    const createCourseBtn = document.getElementById("createCourseBtn");
    const moveCoursesBtn = document.getElementById("moveCoursesBtn");
    const deleteCourseBtn = document.getElementById("deleteCourseBtn");
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");
    let isCreatingCourse = false;
    let isMovingCourse = false;
    let isDeletingCourse = false;
    let selectedCourseIndex = -1;
    let courseName;
    let courses = [];

    createCourseBtn.addEventListener("click", function() {
        courseName = prompt("Enter the course name:");
        isCreatingCourse = true;
        isMovingCourse = false;
        isDeletingCourse = false;
    });

    moveCoursesBtn.addEventListener("click", function() {
        isMovingCourse = true;
        isCreatingCourse = false;
        isDeletingCourse = false;
    });

    deleteCourseBtn.addEventListener("click", function() {
        isDeletingCourse = true;
        isCreatingCourse = false;
        isMovingCourse = false;
    });

    canvas.addEventListener("click", function(event) {
        if (isCreatingCourse) {
            const x = event.clientX - canvas.getBoundingClientRect().left;
            const y = event.clientY - canvas.getBoundingClientRect().top;
            createCourse(x, y);
        } else if (isDeletingCourse) {
            const x = event.clientX - canvas.getBoundingClientRect().left;
            const y = event.clientY - canvas.getBoundingClientRect().top;
            deleteCourse(x, y);
        }
    });

    canvas.addEventListener("mousedown", function(event) {
        if (isMovingCourse) {
            const x = event.clientX - canvas.getBoundingClientRect().left;
            const y = event.clientY - canvas.getBoundingClientRect().top;
            selectedCourseIndex = findCourse(x, y);
            if (selectedCourseIndex !== -1) {
                canvas.addEventListener("mousemove", moveCourse);
            }
        }
    });

    canvas.addEventListener("mouseup", function() {
        canvas.removeEventListener("mousemove", moveCourse);
        selectedCourseIndex = -1;
    });

    
    function createCourse(x, y) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_course.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            const courseId = xhr.responseText; // Get the courseId from the server response
            courses.push({ x: x, y: y, name: courseName, courseId: courseId });
            drawCourses();
        }
    };
    xhr.send(`name=${courseName}&x=${x}&y=${y}`);
}

    function drawCourses() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        courses.forEach(course => {
            ctx.fillStyle = "blue";
            ctx.fillRect(course.x - 25, course.y - 25, 50, 50);
            ctx.fillStyle = "black";
            ctx.fillText(course.name, course.x - 25, course.y - 30);
        });
    }

    function findCourse(x, y) {
        for (let i = 0; i < courses.length; i++) {
            const course = courses[i];
            if (x >= course.x - 25 && x <= course.x + 25 && y >= course.y - 25 && y <= course.y + 25) {
                return i;
            }
        }
        return -1;
    }

    function moveCourse(event) {
    const newX = event.clientX - canvas.getBoundingClientRect().left;
    const newY = event.clientY - canvas.getBoundingClientRect().top;
    const courseId = courses[selectedCourseIndex].courseId;
    courses[selectedCourseIndex].x = newX;
    courses[selectedCourseIndex].y = newY;
    drawCourses();
    updateCoursePosition(courseId, newX, newY);
}

function deleteCourse(x, y) {
    const index = findCourse(x, y);
    if (index !== -1) {
        const course = courses[index];
        const courseId = course.courseId;
        courses.splice(index, 1);
        drawCourses();
        deleteCourseFromDatabase(courseId);
    } else {
        console.error('Course not found');
    }
}

// Function to retrieve courses from the server
function getCourses() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_courses.php', true);
    xhr.onload = function() {
    if (xhr.status === 200) {
        const coursesData = JSON.parse(xhr.responseText);
        if (coursesData.length > 0) {
            courses = coursesData.map(course => ({
                x: course.x,
                y: course.y,
                name: course.name,
                courseId: course.courseId
            }));
            console.log("Retrieved courses:", courses);
        } else {
            courses = [];
        }
        
            drawCourses();
        
    }
};
    xhr.send();
}

getCourses();

});

function updateCoursePosition(courseId, newX, newY) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_course.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Course position updated');
        } else {
            console.error('Error updating course position');
        }
    };
    xhr.send(`courseId=${courseId}&x=${newX}&y=${newY}`);
}

function deleteCourseFromDatabase(courseId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_course.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Course deleted');
        } else {
            console.error('Error deleting course');
        }
    };
    xhr.send(`courseId=${encodeURIComponent(courseId)}`);
}


            
        </script>
        </body>
        </html>


    <?php
    } else {
        echo "<p>You are not logged in.</p>";
    }
    ?>
</section>

