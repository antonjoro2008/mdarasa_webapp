$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const BASEURL = 'https://mdarasa.com:8443/api/v1';
// const BASEURL = 'http://localhost:8080/api/v1';
let cartSubtotal = 0;
var mainCategories = "";
var subCategories = "";

$('#sidebar-subcats').hide();
$('#sidebar-lecturers').hide();
$('#addUserForm').hide();

const urlParams = new URLSearchParams(window.location.search);
const checkoutParam = urlParams.get('checkout');

if (checkoutParam == "success") {
    localStorage.removeItem("cartitems")
}

function toggleForm(elem) {

    if ($('#' + elem).is(":visible")) {

        $('#' + elem).hide();
    } else {
        $('#' + elem).show();
    }
}

let itemsInCart = JSON.parse(localStorage.getItem("cartitems"))

function refreshCart(cartCourseUnits) {

    let itemsInCartDetails = "";
    let formHiddenCartHtml = "";
    itemsInCart = "";
    if (cartCourseUnits !== null) {
        cartCourseUnits.forEach(
            function (item) {
                itemsInCartDetails = itemsInCartDetails + `<div class="mt-12 row">
                <div class="col-md-3">
                    <img src="/images/graduated-cap.webp" width="120px" alt="">
                </div>
                <div class="col-md-7">`+ item.courseUnitName + `<div class="instructor-name">
                `+ item.instructor + `</div>
                </div>
                <div class="col-md-2">
                    KShs. `+ parseFloat(item.price).toLocaleString("en-US") + `
                    <button class="mt-12 mb-12 btn btn-danger" 
                    onclick="removeFromCart(`+ item.courseUnitId + `)">
                        <i class="fa fa-remove"></i> Remove</button>
                </div>
            </div>
            <div class="separator mt-8"></div>`;

                itemsInCart = itemsInCart + item.courseUnitId + ":" + item.price + ",";
                cartSubtotal += parseFloat(item.price)
            }
        )
    }

    formHiddenCartHtml = `<input type="hidden" name="itemsInCart" value="` + itemsInCart + `"/>`;

    if (typeof document.getElementById("courseUnitsIds") !== undefined && document.getElementById("courseUnitsIds") != null) {

        document.getElementById("courseUnitsIds").innerHTML = formHiddenCartHtml;
    }

    return itemsInCartDetails;
}

function getCartSubTotal(cartitems) {

    let total = 0;
    cartitems.forEach(function (item) {
        total += parseFloat(item.price)
    })

    return total;
}

function removeFromCart(unitId) {

    let allItemsInCart = JSON.parse(localStorage.getItem("cartitems"));
    const newCartItems = allItemsInCart.filter(function (item) {
        return item.courseUnitId != unitId;
    });
    console.log(newCartItems)
    localStorage.setItem("cartitems", JSON.stringify(newCartItems));
    $('#cartCartDetails').html(refreshCart(newCartItems))
    $('#cartItems').html(newCartItems.length)
    $('#cartCartItems').html(newCartItems.length)
    $('#cartSubtotal').html(parseFloat(getCartSubTotal(newCartItems)).toLocaleString("en-US"))
    Swal.fire(
        'Removed from Cart!',
        'Course unit removed from the cart successfully!',
        'success'
    )
}

$('#mobiCartItems').html(itemsInCart !== null ? itemsInCart.length : 0);
$('#cartItems').html(itemsInCart !== null ? itemsInCart.length : 0)
$('#cartCartItems').html(itemsInCart !== null ? itemsInCart.length : 0)

$('#cartCartDetails').html(refreshCart(itemsInCart))
cartSubtotal = cartSubtotal.toLocaleString("en-US")
$('#cartSubtotal').html(cartSubtotal)

function loadLecturers(categoryId) {
    axios.post(BASEURL + "/category/lecturers", {
        categoryId: categoryId
    }).then(function (response) {
        console.log(response.data.Data);
        let respJSON = response.data.Data;

        if (respJSON.length == 0) {

            return;
        }

        let responseStr = `<ul>`;

        for (let rec in respJSON) {
            console.log("Record", respJSON[rec]);
            let profile = respJSON[rec];
            responseStr = responseStr + `<li>
                <a class="nav-link" href="/category/lecturer/`+ categoryId + `/` + profile.profileId + `">
                    <i class="fa fa-book"></i> `+ profile.salutation + ` ` + profile.firstName + ` ` +
                profile.lastName + `</a>
            </li>`
        }

        responseStr = responseStr + '</ul>';

        $('#sidebar-lecturers').html(responseStr)
        $('#sidebar-lecturers').show();
    }).catch(function (error) {
        console.error(error);
    });
}

function showMobiSubCategories() {
    $('#sidebarMobiCats').html(subCategories)
}

function loadMobiLecturers(categoryId) {

    subCategories = $('#sidebarMobiCats').html();

    axios.post(BASEURL + "/category/lecturers", {
        categoryId: categoryId
    }).then(function (response) {
        console.log(response.data.Data);
        let respJSON = response.data.Data;

        if (respJSON.length == 0) {

            return;
        }

        let responseStr = `<ul>
            <li class="mobi-menu-header nav-link" onclick="showMobiSubCategories()">
                <i class="fa fa-angle-left right-arrow ml-8"></i>
                <span class="pl-16">Back</span>
            </li>`;

        for (let rec in respJSON) {
            let profile = respJSON[rec];
            responseStr = responseStr + `<li>
                <a class="nav-link" href="/category/lecturer/`+ categoryId + `/` + profile.profileId + `">
                    <i class="fa fa-book"></i> `+ profile.salutation + ` ` + profile.firstName + ` ` +
                profile.lastName + `</a>
            </li>`
        }

        responseStr = responseStr + '</ul>';

        $('#sidebarMobiCats').html(responseStr)

    }).catch(function (error) {
        console.error(error);
    });
}

function hideSubs() {

    $('#sidebar-lecturers').hide();
    $('#sidebar-subcats').hide();
}

function loadCourses(categoryId) {
    axios.post(BASEURL + "/courses/find", {
        categoryId: categoryId
    }).then(function (response) {
        console.log(response.data.Data);
        let respJSON = response.data.Data;

        if (respJSON.length == 0) {
            $('#sidebar-subcats').hide();
            return;
        }
        let responseStr = `<ul>`;

        for (let rec in respJSON) {
            console.log("Record", respJSON[rec]);
            let course = respJSON[rec];
            responseStr = responseStr + `<li onmouseenter="loadLecturers(` + course.courseId + `)">
                <a class="nav-link" href="/course-units/`+ course.courseId + `">
                    <i class="fa fa-book"></i> `+ course.courseName + `
                    <i class="fa fa-angle-right pull-right right-arrow"></i>
                </a>
            </li>`
        }

        responseStr = responseStr + '</ul>';

        $('#sidebar-lecturers').hide();
        $('#sidebar-subcats').hide();
        $('#sidebar-subcats').html(responseStr)
        $('#sidebar-subcats').show();
    }).catch(function (error) {
        console.error(error);
    });
}

function loadSubcategories(categoryId) {

    axios.post(BASEURL + "/subcategories", {
        categoryId: categoryId
    }).then(function (response) {
        console.log(response.data.Data);
        let respJSON = response.data.Data;

        if (respJSON.length == 0) {
            $('#sidebar-subcats').hide();
            return;
        }
        let responseStr = `<ul>`;

        for (let rec in respJSON) {
            console.log("Record", respJSON[rec]);
            let subcategory = respJSON[rec];
            responseStr = responseStr + `<li onmouseenter="loadLecturers(` + subcategory.categoryId + `)">
                <a class="nav-link" href="/course-units/`+ subcategory.categoryId + `">
                    <i class="fa fa-book"></i> `+ subcategory.categoryName + `
                    <i class="fa fa-angle-right pull-right right-arrow"></i>
                </a>
            </li>`
        }

        responseStr = responseStr + '</ul>';

        $('#sidebar-lecturers').hide();
        $('#sidebar-subcats').html(responseStr)
        $('#sidebar-subcats').show();
    }).catch(function (error) {
        console.error(error);
    });
}


function loadMobiMainCategories() {
    $('#sidebarMobiCats').html(mainCategories)
}

function loadMobiSubcategories(categoryId) {

    mainCategories = $('#sidebarMobiCats').html();

    axios.post(BASEURL + "/subcategories", {
        categoryId: categoryId
    }).then(function (response) {
        let respJSON = response.data.Data;

        if (respJSON.length == 0) {
            return;
        }

        let responseStr = `<ul>
            <li class="mobi-menu-header nav-link" onclick="loadMobiMainCategories()">
                <i class="fa fa-angle-left right-arrow ml-8"></i>
                <span class="pl-16">Menu</span>
            </li>`;

        for (let rec in respJSON) {
            console.log("Record", respJSON[rec]);
            let subcategory = respJSON[rec];
            responseStr = responseStr + `<li class="nav-link" onclick="loadMobiLecturers(` + subcategory.categoryId + `)">
                    <i class="fa fa-book"></i> `+ subcategory.categoryName + `
                    <i class="fa fa-angle-right pull-right right-arrow"></i>
            </li>`
        }

        responseStr = responseStr + '</ul>';
        $('#sidebarMobiCats').html(responseStr)
    }).catch(function (error) {
        console.error(error);
    });
}

function addToCart(unitId, unitName, price, instructor) {

    let jsonString = `{"courseUnitId":"` + unitId + `","courseUnitName":"`
        + unitName + `","price":"` + price + `","instructor":"` + instructor + `"}`;

    let items = localStorage.getItem("cartitems");

    if (items != null && items.length > 0) {
        let itemsArray = JSON.parse(items);
        itemsArray.push(JSON.parse(jsonString))
        localStorage.setItem("cartitems", JSON.stringify(itemsArray))
    } else {

        localStorage.setItem("cartitems", '[' + jsonString + ']');
    }

    const newCartItems = JSON.parse(localStorage.getItem("cartitems"));
    $('#cartCartDetails').html(refreshCart(newCartItems))
    $('#cartItems').html(newCartItems.length);
    $('#mobiCartItems').html(newCartItems.length);
    $('#cartCartItems').html(newCartItems.length)
    $('#cartSubtotal').html(parseFloat(getCartSubTotal(newCartItems)).toLocaleString("en-US"))

    Swal.fire(
        'Added to Cart!',
        'Course unit added to your cart successfully!',
        'success'
    )
}

function filterCourses() {

    let categoryId = $("#categoryId").val();

    axios.post(BASEURL + "/courses/find", {
        categoryId: categoryId
    }).then(function (response) {
        console.log(response.data.Data);
        let respJSON = response.data.Data;

        let responseStr = `<option value="">Please select your course</option>`;

        for (let rec in respJSON) {

            let course = respJSON[rec];
            responseStr = responseStr + `<option value="` + course.courseId + `">` + course.courseName + `</option>`

        }

        $('#courseId').html(responseStr)
        $('.categorydesc').addClass("hidden");
        $('#category-' + categoryId + '-description').removeClass("hidden")

    }).catch(function (error) {
        console.error(error);
    });
}

function filterSubcategories() {

    let categoryId = $("#categoryId").val();

    axios.post(BASEURL + "/subcategories", {
        categoryId: categoryId
    }).then(function (response) {
        console.log(response.data.Data);
        let respJSON = response.data.Data;

        let responseStr = `<option value="">Please select sub-category</option>`;

        for (let rec in respJSON) {

            let subcategory = respJSON[rec];
            responseStr = responseStr + `<option value="` + subcategory.categoryId + `">` + subcategory.categoryName + `</option>`

        }

        $('#subcategory').html(responseStr)
        $('.categorydesc').addClass("hidden");
        $('#category-' + categoryId + '-description').removeClass("hidden")

    }).catch(function (error) {
        console.error(error);
    });
}

$('.image-upload-wrap').click(function () { $('#profilePhoto').trigger('click'); });

function showPreview(event) {

    if (event.target.files.length > 0) {
        let src = URL.createObjectURL(event.target.files[0]);
        let preview = document.getElementById("previewProfilePhoto");
        preview.src = src;
        preview.style.display = "block";

        $('.file-upload-content').show();
    }
}

function submitProfilePhoto() {
    $('#profilePhotoForm').submit();
}

function saveVideoUrl() {

    const token = document.querySelector('input[name="_token"]').value;
    const videoTitle = document.getElementById('videoTitle').value;
    const courseUnitSubtopicId = document.getElementById('courseUnitSubtopicId').value;
    const videoNumber = document.getElementById('videoNumber').value;
    const videoUrl = document.getElementById('thirdPartyVideoUrl').value;

    if (videoUrl === "") {

        Swal.fire(
            'Invalid Video URL!',
            'Please provide a valid video URL',
            'error'
        )
    }

    const formData = new FormData();
    formData.append('_token', token)
    formData.append('courseUnitSubtopicId', courseUnitSubtopicId);
    formData.append('videoTitle', videoTitle);
    formData.append('videoNumber', videoNumber);
    formData.append('thirdPartyVideoUrl', videoUrl);
    formData.append('status', 1);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/subtopic/video/thirdparty', true);

    xhr.onload = function () {

        if (xhr.status === 200) {

            Swal.fire(
                'Video Uploaded!',
                'Your course subtopic video has been uploaded successfully!',
                'success'
            )
            window.location.reload();
        } else {

            Swal.fire(
                'Error Adding Your Subtopic Video!',
                'There was a problem adding the subtopic video. Please try again later!',
                'error'
            )
        }

        document.getElementById('blurPage').classList.remove('blur-page');
        $('#please-wait').hide();
        $('#saveVideoButton').prop('disabled', false);
    };

    xhr.onerror = function () {

        Swal.fire(
            'Video Adding Failed!',
            'We could not save your video details. Please try again later!',
            'error'
        )

        document.getElementById('blurPage').classList.remove('blur-page');
        $('#please-wait').hide();
        $('#saveVideoButton').prop('disabled', false);
    };

    xhr.send(formData);
}


function saveVideo() {

    const token = document.querySelector('input[name="_token"]').value;
    const videoSource = document.querySelector('input[name="videoSource"]:checked').value;

    if (videoSource === "url") {
        saveVideoUrl();
    }
    else {
        const fileInput = document.getElementById('subtopicVideoFile');
        const file = fileInput.files[0];
        const formData = new FormData();
        const videoTitle = document.getElementById('videoTitle').value;
        const courseUnitSubtopicId = document.getElementById('courseUnitSubtopicId').value;
        const videoNumber = document.getElementById('videoNumber').value;

        if (!subtopicVideoFile) {

            Swal.fire(
                'Invalid Video File!',
                'Please attach a valid video file',
                'error'
            )
        }

        formData.append('_token', token)
        formData.append('courseUnitSubtopicId', courseUnitSubtopicId);
        formData.append('videoTitle', videoTitle);
        formData.append('videoNumber', videoNumber);
        formData.append('subtopicVideoFile', file);
        formData.append('status', 1);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/subtopic/video/add', true);

        xhr.upload.onprogress = function (event) {

            if (event.lengthComputable) {

                document.getElementById('blurPage').classList.add('blur-page');
                $('#please-wait').show();
                $('#saveVideoButton').prop('disabled', true);
                const progressWrapper = document.getElementById('progressWrapper');
                progressWrapper.style.display = 'block';
                const progress = (event.loaded / event.total) * 100;
                const progressBar = document.getElementById('progressBar');
                progressBar.style.width = progress + '%';
                progressBar.innerHTML = Math.floor(progress) + '%';

                if (progress >= 100) {
                    progressBar.innerHTML = 'Uploading Done, Optimizing your video...';
                }
            }
        };

        xhr.onload = function () {

            if (xhr.status === 200) {

                Swal.fire(
                    'Video Uploaded!',
                    'Your course subtopic video has been uploaded successfully!',
                    'success'
                )
                window.location.reload();
            } else {

                Swal.fire(
                    'Error Uploading File!',
                    'There was a problem uploading the file. Please try again later!',
                    'error'
                )

                const progressBar = document.getElementById('progressBar');
                progressBar.innerHTML = ""
                progressBar.style.width = '0%';
                const progressWrapper = document.getElementById('progressWrapper');
                progressWrapper.style.display = 'none';

            }

            document.getElementById('blurPage').classList.remove('blur-page');
            $('#please-wait').hide();
            $('#saveVideoButton').prop('disabled', false);
        };

        xhr.onerror = function () {

            Swal.fire(
                'Video Upload Failed!',
                'We could not upload your video. Please ensure that the correct video file format is used and that the size is not more than 300MB',
                'error'
            )

            const progressBar = document.getElementById('progressBar');
            progressBar.innerHTML = ""
            progressBar.style.width = '0%';
            const progressWrapper = document.getElementById('progressWrapper');
            progressWrapper.style.display = 'none';

            document.getElementById('blurPage').classList.remove('blur-page');
            $('#please-wait').hide();
            $('#saveVideoButton').prop('disabled', false);
        };

        xhr.send(formData);
    }
}

