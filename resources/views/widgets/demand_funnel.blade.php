<div>
<style>
    .funnel .funnel-level {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        margin-bottom: 3px; 
        padding: 4px; 
        text-align: center;
        border-radius: 5px;
        position: relative;
    }

    .funnel .funnel-level::before {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        width: 0;
        height: 0;
        border-left: 12px solid transparent; 
        border-right: 12px solid transparent; 
        border-top: 5px solid #f8f9fa; 
        margin-left: -12px; 
    }

    .funnel .funnel-level:last-child::before {
        display: none;
    }

    .funnel .level-title {
        font-weight: bold;
    }

    .funnel .level-value {
        font-size: 1.2em;
    }

    /* Specific sizes for each level */
    .funnel .level-apr {
        width: 80%;
        margin-left: 10%;
    }

    .funnel .level-idea {
        width: 60%;
        margin-left: 20%;
    }

    .funnel .level-proposed {
        width: 40%;
        margin-left: 30%;
    }

    .funnel .level-active {
        width: 20%;
        margin-left: 40%;
    }
</style>
    <div class="row">
        <div class="col mt-0">
            <h5 class="card-title">Demand Funnel</h5>
        </div>

        <div class="col-auto">
            <div class="stat text-primary">
                <i class="align-middle" data-feather="compass"></i>
            </div>
        </div>
    </div>
    <div class="funnel mt-3">
        <div class="funnel-level level-apr">
            <div class="level-title">APR</div>
            <div class="level-value">14,212</div>
        </div>
        <div class="funnel-level level-idea">
            <div class="level-title">Idea</div>
            <div class="level-value">10,659</div>
        </div>
        <div class="funnel-level level-proposed">
            <div class="level-title">Proposed</div>
            <div class="level-value">7,241</div>
        </div>
        <div class="funnel-level level-active">
            <div class="level-title">Active</div>
            <div class="level-value">3,890</div>
        </div>
    </div>
</div>
