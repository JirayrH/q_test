<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Question Form Test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div style="width: 60%; margin: auto; border: 1px solid green; border-radius: 12px; padding: 20px">
        <form id="testForm">
            <div class="form-group">
                <label style="display: block">Сколько цветов в радуге? </label>
                <div class="q1-checkboxes" id="q1">
                    <input type="checkbox" name="answers[1]" value="2" checked> 2
                    <input type="checkbox" name="answers[1]" value="7"> 7
                    <input type="checkbox" name="answers[1]" value="14"> 14
                </div>
            </div>
            <div class="form-group">
                <label>Сколько дней в году? </label>
                <input type="text" class="form-control" name="answers[2]" id="q2" required>
            </div>
            <div class="form-group">
                <label for="q3">Кто проживает на дне океана? </label>
                <select class="form-control" name="answers[3]" id="q3" size="1x">
                    <option value="Спанч Боб">Спанч Боб</option>
                    <option value="Медуза">Медуза</option>
                    <option value="Русалка">Русалка</option>
                </select>
            </div>
            <div class="form-group text-center">
                <input type="submit" class="btn btn-md btn-success" value="Отправить">
            </div>
        </form>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    function Test() {
        var that = this;
        this.form = $('#testForm');
        this.fields = {
            1: '#q1 > input:checked',
            2: '#q2',
            3: '#q3'
        };

        this.answers = {};

        /**
         * init prototype
         */
        this.init = function () {
            document.addEventListener("DOMContentLoaded", function () {
                // submit answers
                that.form.on('submit', that.submitAnswers);

                // set stored answers (if exist)
                that.setStoredAnswers();

                // store answers in storage
                $('input, select').not('[type="checkbox"]').on('keyup change', that.storeAnswers);

                // uncheck other checkboxes for Question 1
                $('input[type=checkbox]').on('change', function () {
                    $('input[type="checkbox"]').not(this).prop('checked', false);
                    that.storeAnswers();
                });
            });
        };

        /**
         * store answers in Storage
         */
        this.storeAnswers = function () {
            for (var i in that.fields) {
                that.answers[i] = $(that.fields[i]).val();
            }

            localStorage.setItem('answers', JSON.stringify(that.answers));
        };

        /**
         * set stored answers on page load
         */
        this.setStoredAnswers = function() {
            var storedAnswers = localStorage.getItem('answers');
            if (storedAnswers) {
                storedAnswers = JSON.parse(storedAnswers);

                // set first question
                $('#q1 > input[value='+storedAnswers[1]+']').prop('checked', true);
                $('#q1 > input').first().prop('checked', false);

                // set second question
                $(that.fields[2]).val(storedAnswers[2]);

                // set third question
                $(that.fields[3]).val(storedAnswers[3]);
            }
        };

        /**
         * submit answers
         * @param event
         */
        this.submitAnswers = function (event) {
            event.preventDefault();
            $.ajax({
                url: 'question/submitAnswers',
                method: 'POST',
                data: that.form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.error) {
                        alert(response.message);
                    }

                    document.cookie = "submitted=1";
                    localStorage.removeItem('answers');
                    $('body').html(response.view);
                }
            });
        }
    }

    var t = new Test();
    t.init();
</script>

</body>
</html>