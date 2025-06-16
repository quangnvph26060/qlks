
// const MIN_LENGTH_LOGIN_ID = 8;
// const MAX_LENGTH_LOGIN_ID = 30;
// const MIN_LENGTH_PASSWORD = 8;
// const MAX_LENGTH_PASSWORD = 20;
function checkRequired(value) {
    if (value == "" || value.trim() === "") {
        return false;
    }
    return true;
}
// check in hoa 
function isAllUpperCase(value) {
    return /^[A-Z0-9_-]+$/.test(value);

}

// check number
function checkInteger(value) {
    if (value.match(/^\d+$/)) {
        return true;
    }
    return false;
}
function checkCharacterPhone(value) {
    if (value.match(/^\d{10}$|^\d{11}$/)) {
        return true;
    }
    return false;
}
function checkEmail(value) {
    if(value == "" || value.trim() === "")
    {
        return true;
       
    }
    else
    {
        if (value.match(/^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/)) {
            return true;
        }
        else
        {
            return false;
        }
    }
   
}
function checkLength(value, length) {
    if (value.length != length) {
        return false;
    }
    return true;
}
function checkPass(value) {
    if (value.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/)) {
        return true;
    }
    return false;
}
function checkYear(value, year) {
    if (value >= year) {
        return true;
    }
    return false;
}
function checkURL(value) {
    if (value.match(/^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=]*)?/)) {
        return true;
    }
    return false;

}
function checkKey(value) {
    if (value.match(/^[a-zA-Z0-9]+$/)) {
        return true;
    }
    return false;

}
function validateAllFields(data){
    var isValid = true;
    for (var fieldName in data) {
       if (!validateField(fieldName, data)) {
           isValid = false;
       }
    }
    return isValid;
}
function validateField(fieldName, data) {
    var fieldValue = data[fieldName].element.value;
    var errorContainer = data[fieldName].error;
    var inputElement = data[fieldName].element;
    var validations = data[fieldName].validations;
    var hasError = false;
    var formElement = data[fieldName].element.form;
    for (var i = 0; i < validations.length; i++) {
        if (!validations[i].func(fieldValue)) {
            errorContainer.textContent = validations[i].message;
            inputElement.classList.add("is-invalid");
            hasError = true;
            break;
        }
    }
    if (hasError) {
        var firstInvalidInput = formElement.querySelector('.is-invalid');
        if (firstInvalidInput) {
            firstInvalidInput.scrollIntoView({ block: 'center', behavior: 'smooth' });
            firstInvalidInput.focus();
        }
        return false;
    }
    errorContainer.textContent = "";
    inputElement.classList.remove("is-invalid");
    return true;
}
function generateErrorMessage(code, values = []) {
    const errorMessages = {
        E001:   'Mật khẩu không để trống',
        TKS001: 'Tên khách sạn không được để trống',
        MS001:  'Mã cơ sở không được để trống',
        INHOA:  `${values} phải là in hoa`,
        P001:  `${values} không được để trống`,
        P002: `${values} phải là số`,
        TTT001: 'Tên trạng thái không được để trống',
        KT001: 'Mã code không được chưa ký tự đặc biệt',
        TD001: 'Tiêu đề không được để trống',

        MN001: 'Mã nguồn không được để trống',
        TN001: 'Tên nguồn không được để trống',
        MNH001: 'Mã nhóm không được để trống',
        TNH001: 'Tên nhóm không được để trống',
        TKH001: 'Tên khách hàng không được để trống',
        MKH001: 'Mã khách hàng không được để trống',
        SDT001: `Điện thoại không được để trống`,
        SDT002: `${values} phải là số`,
        Email001: 'Email không được để trống',
        Email002: 'Email không hợp lệ',
        DiaChi001: 'Địa chỉ không được để trống'
        // khai báo thêm các message vào đây ...
    };
    const errorMessage = errorMessages[code];
    if (typeof errorMessage === 'function') {
        return errorMessage(values);
    }
    return errorMessage;
}
