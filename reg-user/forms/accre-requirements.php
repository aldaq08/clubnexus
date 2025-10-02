<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Accreditation Requirements - ISUFST Student Support Center</title>
    <link rel="icon" type="image/c-icon" href="../src/clubnexusicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2d85db;
            --secondary: #0075a3;
            --accent: #ffde59;
            --light: #f5f7fa;
            --dark: #2c3e50;
            --success: #27ae60;
            --warning: #e74c3c;
            --text: #333333;
            --text-light: #7f8c8d;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
            --gradient: linear-gradient(135deg, #2d85db, #ffde59, #0075a3);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: var(--gradient);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background-color: #0075a3;
            color: white;
            padding: 2rem 0;
            box-shadow: var(--shadow);
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            text-align: center;
            width: 100%;
        }
        
        .logo-left {
            width: 70px;
            height: 70px;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow);
            font-size: 2.5rem;
        }
        
        
        .school-info {
            flex: 1;
            min-width: auto;
        }
        
        .school-info h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }
        
        .school-info p {
            font-size: 1rem;
            opacity: 0.9;
            font-family: 'Outfit', sans-serif;
        }
        
        .logo-right {
            width: 70px;
            height: 70px;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow);
            font-size: 2.5rem;
        }
        

        
        /* Main Content Styles */
        .page-title {
            text-align: center;
            margin: 2rem 0;
            color: var(--primary);
            position: relative;
            padding-bottom: 15px;
            font-family: 'Fredoka', sans-serif;
            font-weight: 600;
            font-size: 2.5rem;
        }
        
        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }
        
        .welcome-section {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .welcome-section p {
            margin-bottom: 1rem;
        }
        
        .highlight {
            background-color: rgba(255, 222, 89, 0.1);
            padding: 15px;
            border-left: 4px solid var(--accent);
            margin: 1.5rem 0;
            border-radius: 0 5px 5px 0;
        }
        
        /* Download Button */
        .download-btn {
            font-family: 'Outfit', sans-serif;
            display: block;
            background-color: var(--primary);
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            margin: 1rem auto;
            transition: var(--transition);
            box-shadow: var(--shadow);
            position: relative;
            width: max-content;
            border: none;
            cursor: pointer;
        }
        
        
        .download-btn:hover {
            background-color: #1e6bb0;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Requirements Section */
        .requirements-section {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .section-title {
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light);
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }
        
        .requirement-item {
            margin-bottom: 1.5rem;
            padding-left: 30px;
            position: relative;
        }
        
        .requirement-item:before {
            content: '✓';
            color: var(--success);
            position: absolute;
            left: 0;
            top: 0;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .sub-items {
            margin-top: 10px;
            padding-left: 20px;
        }
        
        .sub-items li {
            margin-bottom: 8px;
            list-style-type: circle;
        }
        
        /* Submission Section */
        .submission-section {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .instructions-list {
            padding-left: 20px;
        }
        
        .instructions-list li {
            margin-bottom: 10px;
        }
        
        /* Notes Section */
        .notes-section {
            background-color: #fff8e1;
            border-left: 4px solid var(--warning);
            border-radius: 0 10px 10px 0;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .notes-section .section-title {
            color: var(--warning);
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }
        
        /* Contact Info */
        .contact-info {
            background: var(--secondary);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .contact-info h2 {
            margin-bottom: 1rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }
        
        .contact-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        
        /* Agreement Section */
        .agreement-section {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
        }
        
        .agreement-section .section-title {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }
        
        .agreement-checkbox {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1.5rem 0;
            font-size: 1rem;
        }
        
        .agreement-checkbox input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }
        
        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
        }
        
        .btn-proceed {
            background-color: var(--success);
            color: white;
        }
        
        .btn-proceed:hover {
            background-color: #219a52;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background-color: var(--warning);
            color: white;
        }
        
        .btn-cancel:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
            font-family: 'Outfit', sans-serif;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .school-info {
                max-width: 100%;
            }
            
            .logo-left, .logo-right {
                order: -1;
            }
            
            .contact-details {
                flex-direction: column;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .buttons-container {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo-left"></div>
                <div class="school-info">
                    <h1>ISUFST Student Support Center</h1>
                    <p>Iloilo State University of Fisheries Science and Technology - San Enrique Campus</p>
                </div>
                <div class="logo-right"></div>
            </div>
        </div>
    </header>

    <main class="container">
        <h2 class="page-title">Organization Accreditation Requirements</h2>
        
        <section class="welcome-section">
            <p>This page outlines the requirements for student organizations seeking accreditation at Iloilo State University of Fisheries Science and Technology (ISUFST), San Enrique Campus.</p>
            
            <div class="highlight">
                <p><strong>Please note: <br> </strong> We are currently only accepting applications for <strong>new organizations</strong>. Renewal applications are not being processed at this time. For further information you may visit the Student Support Center.</p>
            </div>
        </section>
        
        <section class="requirements-section">
            <h3 class="section-title">Requirements for New Organization Accreditation</h3>
            <p>To apply for accreditation, please submit the following documents to the Student Support Center:</p>
            
            <div class="requirement-item">
                <h4>Application Form</h4>
                <p>Complete the "Application Form for Accreditation of Organization." Ensure all sections are filled accurately. Additionally, include the Letter to Apply for Accreditation of Organization, which should be a formal letter from the organization's representative outlining the purpose, objectives, and intent of seeking accreditation. The letter must include the following contents and be signed and dated:</p>
                <ul class="sub-items">
                    <li><strong>Date:</strong> Indicate the date of submission.</li>
                    <li><strong>Organization Name:</strong> Clearly state the full name of the organization.</li>
                    <li><strong>Academic Year & Semester:</strong> Specify the academic year and semester for which accreditation is sought.</li>
                    <li><strong>Name & Signature of Representative:</strong> Provide the name, signature, position/designation of the organization's representative.</li>
                </ul>
            </div>
            
            <div class="requirement-item">
                <h4>Attachments</h4>
                <ul class="sub-items">
                    <li><strong>1. Proposed Constitution & By-Laws:</strong> Submit a copy of the organization's proposed constitution and by-laws. This document should clearly define the organization's purpose, structure, membership, and operational guidelines.</li>
                    <li><strong>2. List of Officially Enrolled Founding Members:</strong> Provide a list of all founding members who are officially enrolled students at ISUFST-San Enrique Campus.</li>
                    <li><strong>3. List of Founding Officers and Faculty Adviser:</strong> Include a list of the organization's founding officers (e.g., President, Vice President, Secretary, Treasurer) and the name of the faculty member who has agreed to serve as the organization's adviser.</li>
                    <li><strong>4. Certificate of Official Enrollment from the Registrar:</strong> Obtain and submit a certificate of official enrollment from the Registrar for all founding members.</li>
                    <li><strong>5. Proposed Target Plan:</strong> Outline the organization's proposed activities, events, and goals for the academic year. This plan should be realistic and aligned with the organization's purpose.</li>
                    <li><strong>6. Security Clearance:</strong> Provide security clearance for all founding members.</li>
                    <li><strong>7. Written Authority from Religious Supervisor (if applicable):</strong> If the organization is religious in nature, provide written authority from the appropriate religious supervisor.</li>
                </ul>
                <button class="download-btn" onclick="downloadForm()">
                    Download Requirements
                </button>
            </div>
        </section>
        
        <section class="submission-section">
            <h3 class="section-title">Submission Instructions</h3>
            <ol class="instructions-list">
                <li>Compile all required documents.</li>
                <li>Submit the complete application attachments to the ISUFST-SEC Student Support Center.</li>
            </ol>
        </section>
        
        <section class="notes-section">
            <h3 class="section-title">Important Notes</h3>
            <ul class="instructions-list">
                <li>Incomplete applications will not be processed.</li>
                <li>The Student Support Center will review all applications and notify organizations of the accreditation decision.</li>
            </ul>
        </section>
        
        <!-- New Agreement Section -->
        <section class="agreement-section">
            <h3 class="section-title">Application Agreement</h3>
            <p>By proceeding, you confirm that all provided information is accurate and that your organization will adhere to ISUFST policies.</p>
            <div class="agreement-checkbox">
                <input type="checkbox" id="agreement" name="agreement">
                <label for="agreement">I agree to the terms and conditions of the.</label>
            </div>
            <div class="buttons-container">
                <button class="btn btn-proceed" onclick="proceedApplication()">Proceed</button>
                <button class="btn btn-cancel" onclick="cancelApplication()">Cancel</button>
            </div>
        </section>
    </main>

    <script>
        function downloadForm() {
            const link = document.createElement('a');
            link.href = '../src/accreditation-req.pdf';
            link.download = 'Accreditation Requirements.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function proceedApplication() {
            const checkbox = document.getElementById('agreement');
            if (checkbox.checked) {
                window.location.href = 'org-accreditation.php';
            } else {
                alert('Please agree to the terms and conditions before proceeding.');
            }
        }
        function cancelApplication() {
            if (confirm('Are you sure you want to cancel?')) {
                window.history.back();
            }
        }
    </script>
</body>
</html>
