<?php require 'header.php';


?>
<style>


    .dropdown-content label {
        display: block;
        margin: 3px 0;
    }

    .dropdown-content input[type="checkbox"] {
        margin: 5px;
    }

    .show {
        display: block;
    }

    /* Multi-step form container */
    .tab-pane {
        font-family: 'Cairo', sans-serif;
    }

    /* Form step container */
    .tab {
        display: none;
    }

    /* Step indicator */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    /* Active step indicator */
    .step.active {
        opacity: 1;
    }

    /* Completed step indicator */
    .step.finish {
        background-color: #4CAF50;
    }

    /* Navigation buttons */
    .btn-secondary, .btn-primary {
        margin-top: 20px;
    }

    /* Make the Previous button appear inline if it's not the first tab */
    .btn-secondary {
        display: none;
    }


    /* Input fields */
    .form-control {
        margin-bottom: 20px;
    }

    /* Step-specific styling */
    #studentCheckboxes, #materialsCheckboxes, #teachersCheckboxes {
        height: 350px;
        overflow-y: auto;
    }

    #studentCheckboxes .form-check, #materialsCheckboxes .form-check, #teachersCheckboxes .form-check {
        margin-bottom: 10px;
    }

    /* Ensure that the form container takes the full width */
    form#multiStepForm {
        width: 100%;
    }

    /* Style the buttons consistently */
    button {
        padding: 10px 20px;
    }

    /* Highlight the current step */
    .step.active {
        background-color: #007bff;
        color: white;
    }

    /* Customize the submit button */
    #studentCheckboxes {
        background-color: #e9e9ff;
    }


    /* Form step container */
    .tab1 {
        display: none;
    }

    /* Step indicator */
    .step1 {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    /* Active step indicator */
    .step1.active {
        opacity: 1;
    }

    /* Completed step indicator */
    .step1.finish {
        background-color: #4CAF50;
    }

    /* Navigation buttons */
    .btn-secondary, .btn-primary {
        margin-top: 20px;
    }

    .invalid {
        color: red;
        border: 1px solid;
    }


    /* Step-specific styling */
    #studentCheckboxes, #materialsCheckboxes, #teachersCheckboxes {
        height: 350px;
        overflow-y: auto;
    }

    #studentCheckboxes .form-check, #materialsCheckboxes .form-check, #teachersCheckboxes .form-check {
        margin-bottom: 10px;
    }

    /* Ensure that the form container takes the full width */
    form#multiStepForm, form#studentToPackageForm {
        width: 100%;
    }


    /* Highlight the current step */
    .step1.active {
        background-color: #007bff;
        color: white;
    }

    .specializations-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px; /* Adjust spacing between items */
    }

    .specialization-item {
        flex: 1 1 calc(33.333% - 10px); /* Adjust based on the desired item width and gap */
        box-sizing: border-box;
    }
</style>
<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();
$sessions = $db->getSessionsDataDetailed();
$materials = $db->getSpecializations();
$students = $db->allStudents();
$teachers = $db->getAllTeachers2();

$teacherSpecializations = [];

foreach ($teachers as $teacher) {
    $teacherId = $teacher['id'];

    // If the teacher is not in the array, add them
    if (!isset($teacherSpecializations[$teacherId])) {
        $teacherSpecializations[$teacherId] = [
            'id' => $teacherId,
            'name' => $teacher['name'],
            'img' => $teacher['img'],
            'specializations' => [],
            'percentage' => '' // Initialize percentage field
        ];
    }

    // Add specialization to the teacher
    if (!in_array($teacher['spec'], $teacherSpecializations[$teacherId]['specializations'])) {
        $teacherSpecializations[$teacherId]['specializations'][] = [$teacher['spec'], $teacher['specializations']];
    }
}


// Convert to JSON for use in JavaScript
$teacherSpecializationsJson = json_encode($teacherSpecializations);


?>

<div class="container  text-right">
    <?php
    if (isset($_GET['message']) && isset($_GET['status'])) {
        // Get the message and status from the query parameters
        $message = urldecode($_GET['message']); // Decode the message
        $status = $_GET['status'];

        // Display the message based on the status
        if ($status === "success") {
            echo '<div class="alert alert-success alert-dismissible" role="alert">' . $message . '<span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>' . '</div>';
        } elseif ($status === "error") {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">' . $message . '<span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>' . '</div>';
        }
    }
    ?>

    <ul class="nav nav-tabs nav-pills nav-fill justify-content-center mb-4">

        <li class="nav-item">
            <a class="nav-link active" id="new-package-tab" data-toggle="tab" href="#new-package" role="tab"
               aria-controls="new-package"
               aria-selected="true">إضافة دورات جديدة</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="student-to-package-tab" data-toggle="tab" href="#student-to-package"
               role="tab"
               aria-controls="student-to-package"
               aria-selected="false">إضافة طلاب الى دورات موجودة</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="new-package" role="tabpanel" aria-labelledby="new-package-tab">
            <?php require 'sessions/addNewSession.php'; ?>
        </div>
        <div class="tab-pane fade" id="student-to-package" role="tabpanel" aria-labelledby="student-to-package-tab">
            <?php require 'sessions/addToExistingSession.php'; ?>
        </div>
    </div>
</div>


<script>
    // Enable or disable percentage select based on checkbox state
    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const teacherId = this.value;
            const percentageSelect = document.getElementById('percentage_' + teacherId);
            if (this.checked) {
                percentageSelect.disabled = false;
            } else {
                percentageSelect.disabled = true;
                percentageSelect.value = ''; // Clear the select if unchecked
            }
        });
    });
</script>



<!-- <script>
    let currentTab1 = 0; // Current tab is set to be the first tab (0)
    showTab1(currentTab1); // Display the current tab

    function showTab1(n) {
        // This function will display the specified tab of the form
        let x = document.getElementsByClassName("tab1");
        x[n].style.display = "block";
        // Fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn1").style.display = "none";
        } else {
            document.getElementById("prevBtn1").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn1").innerHTML = "حفظ";
        } else {
            document.getElementById("nextBtn1").innerHTML = "التالي";
        }
        // Run a function that displays the correct step indicator:
        fixStepIndicator1(n)
    }

    function nextPrev1(n) {

        // This function will figure out which tab to display
        let x = document.getElementsByClassName("tab1");

        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm1()) return false;
        // Hide the current tab:
        x[currentTab1].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab1 = currentTab1 + n;
        // if you have reached the end of the form... :
        if (currentTab1 >= x.length) {
            //...the form gets submitted:
            document.getElementById("studentToPackageForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab1(currentTab1);
    }

    function validateForm1() {
        // This function deals with validation of the form fields
        let x, y, i, valid = true;
        x = document.getElementsByClassName("tab1");
        y = x[currentTab1].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:

        for (i = 1; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false
                valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab1].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator1(n) {
        // This function removes the "active" class of all steps...
        let i, x = document.getElementsByClassName("step1");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class to the current step:
        x[n].className += " active";
    }
</script> -->


<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
    $(document).ready(function () {
        $('#reports').DataTable({
            "paging": true,          // Enable pagination
            "lengthChange": true,    // Show length change options
            "searching": true,       // Enable search functionality
            "ordering": true,        // Enable column sorting
            "info": true,            // Show table information
            "autoWidth": false,      // Disable automatic column width adjustment
            "language": {
                "paginate": {
                    "previous": "السابق",
                    "next": "التالي",
                    "first": "الأول",
                    "last": "الأخير"
                },
                "lengthMenu": "عرض _MENU_ سجلات",
                "info": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                "infoEmpty": "لا توجد سجلات متاحة",
                "infoFiltered": "(تمت تصفيته من _MAX_ إجمالي السجلات)",
                "search": "بحث:",
                "zeroRecords": "لم يتم العثور على تطابقات"
            },
            "order": [[0, 'desc']],
            buttons: [
                'excel', 'print', {
                    text: 'PDF',
                    action: function (e, dt, button, config) {
                        // Get the table headers
                        var headers = [];
                        $('#dt-filter-search-income thead th').each(function () {
                            headers.push($(this).text());
                        });

                        // Get the table data
                        var data = [];
                        dt.rows({search: 'applied'}).every(function () {
                            let row = [];
                            $(this.node()).find('td').each(function () {
                                row.push($(this).text());
                            });
                            data.push(row);
                        });

                        // Create a form and submit it
                        let form = $('<form>', {
                            action: '../mpdf-generator.php',
                            method: 'POST'
                        }).append($('<input>', {
                            type: 'hidden',
                            name: 'headers',
                            value: JSON.stringify(headers)
                        })).append($('<input>', {
                            type: 'hidden',
                            name: 'tableData',
                            value: JSON.stringify(data)
                        })).append($('<input>', {
                            type: 'hidden',
                            name: 'reportType',
                            value: 'income_report'
                        }));

                        form.appendTo('body').submit();
                    }
                }
            ],


            initComplete: function () {
                this.api().columns().every(function () {
                    let column = this;
                    let search = $(`<input class="form-control form-control-sm" type="text" placeholder="بحث">`)
                        .appendTo($(column.footer()).empty())
                        .on('change input', function () {
                            let val = $(this).val()

                            column
                                .search(val ? val : '', true, false)
                                .draw();
                        });

                });
            }
        });

    });
</script>

