@extends ('layouts.app')


@section('content')


    <?php
    $permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "report_CVSS");
    ?>

    @if ($permission)

    <h2 align="center">Editace řádku CVSS </h2>

    <?php

    try{
        $id = $row->id;
    }catch(Exception $e){
        echo 'Tato položka nebyla nalezena, prosím zkontrolujte zadání! (Zkontrolujte zpracování hodnot -> Reporty-> CVSS)';
        echo '<br>';
        echo '<a href=\'javascript:history.back(1);\'>Zpět!</a>';
        return 0;
    }


    ?>


    <form class="form-horizontal" action="update" method="POST">
        <input type="hidden" name="ID" value="{{$id}}" >
        {{ csrf_field() }}

        <h2 align="center"> BASE</h2>
        <div class="form-group">
            <label class="control-label col-sm-2">FalsePositive</label>
            <div class="col-sm-10">
                <select class="form-control"  name="falsePositive">
                    <option value="{{$row->falsePositive}}">{{$row->falsePositive}}</option>
                    <option value="true" >1 - True</option>
                    <option value="false" >0 - FALSE</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Access Vector (AV)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="AV">
                    <option value="{{$row->AV}}">{{$row->AV}}</option>
                    <option value="N" >Network</option>
                    <option value="A" >Adjacent Network</option>
                    <option value="L" >Local</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Attack Complexity (AC)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="AC">
                    <option value="{{$row->AC}}">{{$row->AC}}</option>
                    <option value="L" >Low</option>
                    <option value="M" >Medium</option>
                    <option value="H" >High</option>

                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Authentication (Au)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="Au">
                    <option value="{{$row->Au}}">{{$row->Au}}</option>
                    <option value="M" >Multiple</option>
                    <option value="S" >Single</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>




        <div class="form-group">
            <label class="control-label col-sm-2" >Confidentiality Impact (C)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="C">
                    <option value="{{$row->C}}">{{$row->C}}</option>
                    <option value="C" >Complete</option>
                    <option value="P" >Partial</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>



        <div class="form-group">
            <label class="control-label col-sm-2" >Integrity Impact(I)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="I">
                    <option value="{{$row->I}}">{{$row->I}}</option>
                    <option value="C" >Complete</option>
                    <option value="P" >Partial</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Availability Impact (A)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="A">
                    <option value="{{$row->A}}">{{$row->A}}</option>
                    <option value="C" >Complete</option>
                    <option value="P" >Partial</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>



        <h2 align="center">TEMP metric</h2>


        <div class="form-group">
            <label class="control-label col-sm-2" >Exploitability (E)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="E">
                    <option value="{{$row->E}}">{{$row->E}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="F" >Functional</option>
                    <option value="POC" >Proof of Concept</option>
                    <option value="U" >Unproven</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Remediation level (RL)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="RL">
                    <option value="{{$row->RL}}">{{$row->RL}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="W" >Workaround</option>
                    <option value="TF" >Temporary fix</option>
                    <option value="OF" >Official fix</option>
                    <option value="U" >Unavailable</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Report Confidence (RC)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="RC">
                    <option value="{{$row->RC}}">{{$row->RC}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="C" >Confirmed</option>
                    <option value="UC" >Unconfirmed </option>
                    <option value="UR" >Uncorroborated </option>
                </select>
            </div>
        </div>

        <h2 align="center">Enviromental metric</h2>

        <div class="form-group">
            <label class="control-label col-sm-2" >Collateral Damage Potential (CDP)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="CDP">
                    <option value="{{$row->CDP}}">{{$row->CDP}}</option>
                    <option value="N" >None</option>
                    <option value="L" >Low</option>
                    <option value="LM" >Low - Medium</option>
                    <option value="MH" >Medium - High</option>
                    <option value="H" >High</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Target Distribution (TD)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="TD">
                    <option value="{{$row->TD}}">{{$row->TD}}</option>
                    <option value="N" >None</option>
                    <option value="L" >Low</option>
                    <option value="M" >Medium</option>
                    <option value="H" >High</option>
                    <option value="ND" >Not Defined</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Confidentiality Requirement (CR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="CR">
                    <option value="{{$row->CR}}">{{$row->CR}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Integrity Requirement (IR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="IR">
                    <option value="{{$row->IR}}">{{$row->IR}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Availability Requirement(AR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="AR">
                    <option value="{{$row->AR}}">{{$row->AR}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>







        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Zpracovat</button>
            </div>
        </div>
    </form>

    @else
        <h1 align="center" > Na tuto operaci namáte oprávnění!  :-)</h1>
    @endif

@endsection
