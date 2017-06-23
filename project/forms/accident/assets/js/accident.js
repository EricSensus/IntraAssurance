$(function () {
    if ($('#accident_cover_details').length) {
        var can_display = false;
        var counter = $('select#howmany');
        var enabler = $('input[name=other_covers]');
        counter.prop('disabled', true);
        var loadash = $('div#other_covers_div');

        enabler.change(function () {
            if (this.value === 'yes') {
                can_display = true;
                counter.prop('disabled', false);
                counter.val(1).trigger('change');
            } else {
                can_display = false;
                counter.prop('disabled', true);
                loadash.html("");
            }
        });
        var Accident = {
            relationships: {
                wife: "Wife",
                husband: "Husband",
                son: "Son",
                daughter: "Daughter",
                other: "Other"
            },
            agesBracket: {},
            loadMore: function (no) {
                var inputs = '';
                if (no) {
                    for (var i = 1; i <= no; i++) {
                        inputs += '<div class="row">';
                        inputs += '<div class="col-md-2">';
                        inputs += '<div class="form-group">';

                        inputs += '<label>Name</label>';
                        inputs += '<input type="text" name="other_name' + i + '" class="form-control"/>';

                        inputs += '</div>';
                        inputs += '</div>';

                        inputs += '<div class="col-md-2">';
                        inputs += '<div class="form-group">';

                        inputs += '<label>Relationship</label>';
                        inputs += '<select name="other_relationship' + i + '" class="form-control">' +
                            '<option value="Wife">Wife</option> ' +
                            '<option value="Husband">Husband</option> ' +
                            '<option value="Son">Son</option>' +
                            '<option value="Daughter">Daughter</option>' +
                            '<option value="Other">Other</option> ' +
                            '</select>';

                        inputs += '</div>';
                        inputs += '</div>';

                        inputs += '<div class="col-md-2">';
                        inputs += '<div class="form-group">';

                        inputs += '<label>Age Bracket</label>';
                        inputs += '<select name="other_bracket' + i + '" class="form-control">' +
                            '<option value="3-17">3-17</option>' +
                            '<option value="18 - 21">18 - 21</option>' +
                            '<option value="22 - 25">22 - 25</option>' +
                            '<option value="26 - 30">26 - 30</option>' +
                            '<option value="31 - 40">31 - 40</option>' +
                            '<option value="41 - 50">41 - 50</option>' +
                            '<option value="51 - 60">51 - 60</option>' +
                            '<option value="61 - 69">61 - 69</option>' +
                            '<option value="70 or over">70 or over</option>' +
                            '</select>';

                        inputs += '</div>';
                        inputs += '</div>';

                        inputs += '<div class="col-md-2">';
                        inputs += '<div class="form-group">';

                        inputs += '<label>Education</label>';
                        inputs += '<select name="other_education' + i + '" class="form-control">' +
                            '<option value="Primary">Primary</option>' +
                            '<option value="Secondary">Secondary</option>' +
                            '<option value="College">College</option>' +
                            '</select>';

                        inputs += '</div>';
                        inputs += '</div>';

                        inputs += '<div class="col-md-2">';
                        inputs += '<div class="form-group">';

                        inputs += '<label>Band</label>';
                        inputs += '<select name="other_band' + i + '" class="form-control">' +
                            '<option value="band1">Band 1</option>' +
                            '<option value="band2">Band 2</option>' +
                            '<option value="band3">Band 3</option>' +
                            '<option value="band4">Band 4</option>' +
                            '<option value="band5">Band 5</option>' +
                            '<option value="band6">Band 6</option>' +
                            '<option value="band7">Band 7</option>' +
                            '</select>';

                        inputs += '</div>';
                        inputs += '</div>';

                        inputs += '<div class="col-md-2">';
                        inputs += '<div class="form-group">';

                        inputs += '<label>Plan</label>';
                        inputs += '<select name="other_class' + i + '" class="form-control">' +
                            '<option value="class1">Class I</option>' +
                            '<option value = "class2">Class II </option>' +
                            '</select>';

                        inputs += '</div>';
                        inputs += '</div>';

                        inputs += '</div>';
                        inputs += '<hr/>';
                    }
                }
                loadash.html(inputs);
            }
        };
        counter.change(function () {
            if (can_display) {
                Accident.loadMore(parseInt($(this).val()));
            }
        });
    }
});