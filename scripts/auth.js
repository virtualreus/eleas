$(document).ready(function () {
    $("#regInput").click(function(event){
        event.preventDefault();
        var login = $("#register_login").val();
        var email = $("#register_email").val();
        var password=$("#register_password").val();
        var password_c=$("#register_password_confirm").val();

        $.ajax({
            type:"post",
            url:"setting/action_register.php",
            data:{
                login:login,
                email:email,
                password:password,
                password_c:password_c
            },
            success:function(data){
                $("#inform").html(data);
            }
        });
    });



    $("#regInputMobile").click(function(event){
        event.preventDefault();
        var login = $("#register_login_mobile").val();
        var email = $("#register_email_mobile").val();
        var password=$("#register_password_mobile").val();
        var password_c=$("#register_password_confirm_mobile").val();

        $.ajax({
            type:"post",
            url:"setting/action_register.php",
            data:{
                login:login,
                email:email,
                password:password,
                password_c:password_c
            },
            success:function(data){
                $("#inform").html(data);
            }
        });
    });

    
    $("#logInput").click(function(event){
        event.preventDefault();
        let login = $("#login_login").val();
        let password = $("#login_password").val();

        $.ajax({
            type:"post",
            url:"setting/action_login.php",
            data:{
                login:login,
                password:password,
            },
            success:function(data){
                $("#inform_login").html(data);
            }
        });
    });

    $("#logInputMobile").click(function(event){
        event.preventDefault();
        let login = $("#login_login_mobile").val();
        let password = $("#login_password_mobile").val();

        $.ajax({
            type:"post",
            url:"setting/action_login.php",
            data:{
                login:login,
                password:password,
            },
            success:function(data){
                $("#inform_login").html(data);
            }
        });
    });


    $(".printThisListModal").click(function () {
        $("#hover").css("display", "flex");
        $("#ModalPrint").css("display", "flex");

        $(".close-adding-5").click(function () {
            $("#hover").css("display", "none");
            $("#ModalPrint").css("display", "none");
            return false;
        });
    })



    $("#saveListMain").click(function () {
        let id = getUrlParameter('l');
        let newname = $("#newListName").val();
        let recipestobase = "";

        let rawi = 1
        for (let i = 1; i <= 7; i++) {
            recipestobase += ($("#product" + rawi).val() + "|" + $("#product" + (rawi + 1)).val() + "|" + $("#product" + (rawi + 2)).val()) + ";";
            rawi += 3;
        }
        $.ajax({
            type:"post",
            url:"setting/checkListSave.php",
            data:{
              id:id,
              newname:newname,
              products:recipestobase,
            },
            success: function (data) {
                $("#informListSave").html(data);
            }
        })
    })




    $(".addIngrToList").click(function (e) {
        e.preventDefault();
        $("#informModalMain").html('');
            $(".close-adding_3").off().click(function () {
                $("#hover").css("display", "none");
                $("#addRecipeList").css("display", "none");
                return false;
            });
        $("#hover").css("display", "flex");
        $("#addRecipeList").css("display", "flex");
        let classes = this.className.split(" ");
        let belongs = classes[2];
        let classid = classes[1];
            $.ajax({
                type:"post",
                url:"setting/checkListModal.php",
                data:{
                    belongs:belongs,
                },
                success:function (data) {
                    $(".pickInput").off().on('keyup', function (event) {
                        event.preventDefault();
                        let vals = this.value

                        $.ajax({
                            type:"post",
                            url:"setting/checkListFind.php",
                            data: {
                                belongs: belongs,
                                whatfind: vals,
                            },
                            success:function (data) {
                                $("#informModalMain").html(data);
                                $(".setThisToList").click(function () {
                                    let recipename = this.alt;
                                    $("#" + classid).val(recipename);
                                    $("#hover").css("display", "none");
                                    $("#addRecipeList").css("display", "none");
                                    $("#informModalMain").html('');
                                    return false;
                                })
                            }
                        })

                        return false;
                    })
                    $("#informModalMain").html(data);
                    $(".setThisToList").click(function () {
                        let recipename = this.alt;
                        $("#" + classid).val(recipename);
                        $("#hover").css("display", "none");
                        $("#addRecipeList").css("display", "none");
                        $("#informModalMain").html('');
                        return false;
                    })
                }
            })

    })





    $(".clearField").click(function () {
        let allclasses = this.className.split(" ")
        let classid = allclasses[1];
        $("#" + classid).val("");
    })



    $(".addRecipe").click(function () {
        $("#hover").css("display", "flex");
        $("#modalAddRecipe").css("display", "flex");
    });


    $(".buttonMiniRecipesGO").click(function (event) {
        $("#miniRecipes").css("display", "flex");
        $("#checkLists").css("display", "none");
        $("#Recipes").css("display", "none");
        $(".buttonMiniRecipesGO").addClass('navhover');
        $(".buttonCheckListsGO").removeClass('navhover');
        $(".buttonRecipesGO").removeClass('navhover');
    })

    $(".buttonCheckListsGO").click(function (event) {
        $("#miniRecipes").css("display", "none");
        $("#checkLists").css("display", "flex");
        $("#Recipes").css("display", "none");
        $(".buttonCheckListsGO").addClass('navhover');
        $(".buttonMiniRecipesGO").removeClass('navhover');
        $(".buttonRecipesGO").removeClass('navhover');
    })


    $(".buttonRecipesGO").click(function (event) {
        $("#miniRecipes").css("display", "none");
        $("#checkLists").css("display", "none");
        $("#Recipes").css("display", "flex");
        $(".buttonCheckListsGO").removeClass('navhover');
        $(".buttonMiniRecipesGO").removeClass('navhover');
        $(".buttonRecipesGO").addClass('navhover');
    })


    $('.nones').click(function () {
        let newarray = this.className.split(" ");
        $("#" + newarray[1]).val("fasdfdsa");
    })




    $(".close-adding").click(function () {
        $("#hover").css("display", "none");
        $("#modalAddRecipe").css("display", "none");
    });


    $(".addCheckList").click(function () {
        $("#hover").css("display", "flex");
        $("#modalAddCheckLists").css("display", "flex");
    });

    $(".close-adding_2").click(function () {
        $("#hover").css("display", "none");
        $("#modalAddCheckLists").css("display", "none");
    });


    $(".createRecipe").click(function () {
        $("#hover").css("display", "flex");
        $("#modalCreateRecipe").css("display", "flex");
    });

    $(".close-recipe").click(function () {
        $("#hover").css("display", "none");
        $("#modalCreateRecipe").css("display", "none");
    });


    $(".welcome-button2").click(function () {
        $("#popur").css("display", "flex");
        $("#hover").css("display", "flex");
    });

    $(".close-button").click(function () {
        $("#popur").css("display", "none");
        $("#hover").css("display", "none");
    });



    $('.navall').click(function () {
        let perm = "navall";
        $('.navall').addClass('navhover');

        $('.navzav').removeClass('navhover');
        $('.navobed').removeClass('navhover');
        $('.navyjin').removeClass('navhover');
        $('.navsalat').removeClass('navhover');

        $.ajax({
            type:"post",
            url:"setting/proceedMini.php",
            data:{
                perm:perm,
            },
            success:function(data){
                $("#navallblock").html(data);
            }
        });

    });


    $('.navzav').click(function () {
        let perm1 = "navzav";
        $('.navzav').addClass('navhover');

        $('.navall').removeClass('navhover');
        $('.navobed').removeClass('navhover');
        $('.navyjin').removeClass('navhover');
        $('.navsalat').removeClass('navhover');

        $.ajax({
            type:"post",
            url:"setting/proceedMini.php",
            data:{
                perm:perm1,
            },
            success:function(data){
                $("#navallblock").html(data);
            }
        });

    });

    $('.navobed').click(function () {
        let perm2 = "navobed";

        $('.navobed').addClass('navhover');

        $('.navall').removeClass('navhover');
        $('.navzav').removeClass('navhover');
        $('.navyjin').removeClass('navhover');
        $('.navsalat').removeClass('navhover');

        $.ajax({
            type:"post",
            url:"setting/proceedMini.php",
            data:{
                perm:perm2,
            },
            success:function(data){
                $("#navallblock").html(data);
            }
        });
    });

    $('.navyjin').click(function () {
        let perm3 = "navyjin";
        $('.navyjin').addClass('navhover');

        $('.navall').removeClass('navhover');
        $('.navobed').removeClass('navhover');
        $('.navzav').removeClass('navhover');
        $('.navsalat').removeClass('navhover');

        $.ajax({
            type:"post",
            url:"setting/proceedMini.php",
            data:{
                perm:perm3,
            },
            success:function(data){
                $("#navallblock").html(data);
            }
        });

    });


    $('.navsalat').click(function () {
        let perm4 = "navsalat";
        $('.navsalat').addClass('navhover');

        $('.navall').removeClass('navhover');
        $('.navobed').removeClass('navhover');
        $('.navyjin').removeClass('navhover');
        $('.navzav').removeClass('navhover');
        $.ajax({
            type:"post",
            url:"setting/proceedMini.php",
            data:{
                perm:perm4,
            },
            success:function(data){
                $("#navallblock").html(data);
            }
        });

    });


    $("#recipeCols").keypress(function(event){
        event = event || window.event;
        if (event.charCode && event.charCode!=0 && event.charCode!=46 && (event.charCode < 48 || event.charCode > 57) )
            return false;
    });

    $("#recipeCols2").keypress(function(event){
        event = event || window.event;
        if (event.charCode && event.charCode!=0 && event.charCode!=46 && (event.charCode < 48 || event.charCode > 57) )
            return false;
    });


    $(".welcome-button3").click(function (event) {
        $.ajax({
            type:"post",
            url:"setting/editdesc.php",
            data:{
                newdesc:$(".editDesc").val(),
            },
            success:function(data){
                $("#inform_edit").html(data);
            }
        });
    });


    $(".listCont").click(function () {
        let listname = $("#nameOfList").val()
        $.ajax({
            type:"post",
            url:"setting/makeсhecklist.php",
            data:{
                name:listname,
            },
            success:function(data){
                $("#inform_recipe").html(data);
            }
        });

    })


    $(".createRecCont").click(function () {
        let recname = $("#name_Recipe").val()
        $.ajax({
            type:"post",
            url:"setting/createrecipe.php",
            data:{
                name:recname,
            },
            success:function(data){
                $("#informrec").html(data);
            }
        });

    })



    $(".recipeCont").click(function (event) {
        let recipename = $("#nameOfRecipe").val();
        let ingestionBlock = document.getElementById("selectZOYS")
        let ingestion = ingestionBlock.options[ingestionBlock.selectedIndex].value;
        let ingestion_text = ingestionBlock.options[ingestionBlock.selectedIndex].text;

        $.ajax({
            type:"post",
            url:"setting/makerecipe.php",
            data:{
                name:recipename,
                ingestion:ingestion,
                ingestion_text:ingestion_text,
            },
            success:function(data){
                $("#inform_recipe").html(data);
            }


        });
    });




    $("#saveEdits").click(function () {
        let id = getUrlParameter('r');
        let ingreds = "";
        let n_count = $("#n_count").text();
        let newname = $("#newnamerec").val();
        let algo = $("#algoEdit").val();

        let ingestionBlock = document.getElementById("selectZOYSedit")
        let ingestion = ingestionBlock.options[ingestionBlock.selectedIndex].value;
        let ingestion_text = ingestionBlock.options[ingestionBlock.selectedIndex].text;


        for (let i = 1; i <= n_count; i++) {
            // let ingred = $(".ingred" + i).val() == '' ? "Пустой продукт" : $(".ingred" + i).val()
            // let doza = $(".doza" + i).val() == '' ? "Пустая дозировка" : $(".doza" + i).val()
            // ingreds += (ingred) + " " + (doza) + "\n";
            let ingred = $(".ingred" + i).val() == '' ? "" : $(".ingred" + i).val()
            let doza = $(".doza" + i).val() == '' ? "" : $(".doza" + i).val()
            ingreds += (ingred) + " - " + (doza) + "<br>";
        }

        $.ajax({
            type:"post",
            url:"setting/editrecipe.php",
            data:{
                id:id,
                newname:newname,
                ingreds:ingreds,
                algo:algo,
                ingestion:ingestion,
                ingestion_text:ingestion_text,
            },
            success:function(data){
                $("#inform_edit").html(data);
            }

    });
})

    $("#addIngr").click(function (event) {
        event.preventDefault();
        let id = getUrlParameter('r');
        let n_count = $("#n_count").text();
        let ingreds = "";



        for (let i = 1; i <= n_count; i++) {
            // let ingred = $(".ingred" + i).val() == '' ? "Пустой продукт" : $(".ingred" + i).val()
            // let doza = $(".doza" + i).val() == '' ? "Пустая дозировка" : $(".doza" + i).val()
            // ingreds += (ingred) + " " + (doza) + "\n";
            let ingred = $(".ingred" + i).val() == '' ? "" : $(".ingred" + i).val()
            let doza = $(".doza" + i).val() == '' ? "" : $(".doza" + i).val()
            ingreds += (ingred) + " - " + (doza) + "<br>";
        }

        $.ajax({
            type:"post",
            url:"setting/addingr.php",
            data:{
                id:id,
                ingreds:ingreds,
            },
            success:function(data){
                $(".ingreds").html(data);
            }

        });
    })


    $("#delIngr").click(function (event) {
        event.preventDefault();
        let id = getUrlParameter('r');
        let n_count = $("#n_count").text();
        let ingreds = "";

        for (let i = 1; i <= n_count; i++) {
            let ingred = $(".ingred" + i).val() == '' ? "" : $(".ingred" + i).val()
            let doza = $(".doza" + i).val() == '' ? "" : $(".doza" + i).val()
            ingreds += (ingred) + " - " + (doza) + "<br>";
        }

        $.ajax({
            type:"post",
            url:"setting/delingr.php",
            data:{
                id:id,
                ingreds:ingreds,
            },
            success:function(data){
                $(".ingreds").html(data);
            }

        });
    })



    $(".deleteRecipe").click(function (event) {
        let id = getUrlParameter('r');
        $.ajax({
            type:"post",
            url:"setting/delrec.php",
            data:{
                id:id,
            },
            success:function(data){
                $("#informDel").html(data);
            }
        });
    });

    $("#deleteListMain").click(function (event) {
        let id = getUrlParameter('l');
        $.ajax({
            type:"post",
            url:"setting/dellist.php",
            data:{
                id:id,
            },
            success:function(data){
                $("#informDel").html(data);
            }
        });
    });


    $(".subButtonButton").click(function (event) {
        let userid = getUrlParameter('u');
        $.ajax({
            type:"post",
            url:"setting/subprocess.php",
            data:{
                userid:userid,
            },
            success:function(data){
                $("#subButtonBlock").html(data);
            }
        });
    })


    $(".addElemToListButton").click(function (event) {
        let data_id = this.value;
        if ($(this).hasClass('addElemAdded')) {
            $(this).removeClass('addElemAdded');
            $(this).html('+');
        } else {
            $(this).addClass('addElemAdded')
            $(this).html('-');
        }
        $.ajax({
            type:"post",
            url:"setting/addtofav.php",
            data:{
                data_id:data_id,
            },
            success:function(data){
                $("#addToFav").html(data);
            }
        });

    })



    $(".addElemToListButton_in").click(function (event) {
        let data_id = this.value;
        if ($(this).hasClass('addElemAdded')) {
            $(this).removeClass('addElemAdded');
            $(this).html('+');
        } else {
            $(this).addClass('addElemAdded')
            $(this).html('-');
        }
        $.ajax({
            type:"post",
            url:"setting/addtofav.php",
            data:{
                data_id:data_id,
            },
            success:function(data){
                $("#addToFav_in").html(data);
            }
        });

    })


    $('#nav-search').click(function () {
        $('#hover').css('display', 'flex')
        $('#modalSearch').css('display', 'flex')

        $('.close-search').click(function () {
            $('#hover').css('display', 'none')
            $('#modalSearch').css('display', 'none')
        })
    })

    $('#nav-search-mob').click(function () {
        $('#hover').css('display', 'flex')
        $('#modalSearch').css('display', 'flex')

        $('.close-search').click(function () {
            $('#hover').css('display', 'none')
            $('#modalSearch').css('display', 'none')
        })
    })

    $('#findButton').click(function () {
        let querry = $("#searchField").val()
        $.ajax({
            type:"post",
            url:"setting/redirectsearch.php",
            data:{
                querry:querry,
            },
            success:function(data){
                $("#informing").html(data);
            }
        });
    })


    $('#saveRecipeEdits').click(function (event) {
        let newname = $("#newRecName").val();
        let id = getUrlParameter('r');
        let cols_ingreds = $("#colsOfIngred").val();
        let cols_steps = $("#colsOfSteps").val();
        let desc = $("#recipeDescArea").val();
        let parentid = document.getElementById("ingredsBlock");
        let parentid_steps = document.getElementById("stepsIdBlock");

        let countofingrs = parentid.getElementsByClassName("mr_ingredStyle").length;
        let countofsteps = parentid_steps.getElementsByClassName("getRecDescStyle").length;

        let ingreds_to_base = "";
        for (let i = 1; i <= countofingrs; i++) {
            let ingred = $(".mr_Ingred" + i).val() == '' ? "NONE" : $(".mr_Ingred" + i).val()
            let kolvo = $(".mr_Kolvo" + i).val() == '' ? "NONEDOZ" : $(".mr_Kolvo" + i).val()
            ingreds_to_base += (ingred + "|d|s|"+ kolvo +"-|-");
        }

        let datasteps_to_base = "";
        for (let i = 1; i <= countofsteps; i++) {
            let step = $(".rec_Desc" + i).val() == '' ? "NONE" : $(".rec_Desc" + i).val()
            // datasteps_to_base += (ingred + "|d|s|"+ kolvo +"-|-");
            datasteps_to_base += (step + "-|-");
        }

        let categoryid = document.getElementById("selectRecipeType")
        let ingestion = categoryid.options[categoryid.selectedIndex].value;

        $.ajax({
            type:"post",
            url:"setting/saverecipeupdates.php",
            data:{
                id:id,
                name:newname,
                cols_ingreds:cols_ingreds,
                cols_steps:cols_steps,
                ingreds:ingreds_to_base,
                desc:desc,
                stepsdate:datasteps_to_base,
                categoryid:ingestion,
            },
            success:function(data){
                $("#informik").html(data);
            }
        });
    })

    $("#sendReview").click(function () {
        let review_text = $("#ReviewText").val();
        let recipeid = getUrlParameter('r');
        if (review_text) {
            $.ajax({
                type: "post",
                url: "setting/postreview.php",
                data: {
                    recipeid: recipeid,
                    review_text: review_text,
                },
                success: function (data) {
                    $("#allReviews").html(data);
                    $("#ReviewText").val("");
                }
            });
        }


    })
    


    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };



})