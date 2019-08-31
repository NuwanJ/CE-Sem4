//***************************************************
//
//  Instruction Format:   
//			[31--  OP-Code -- 24] [23-- DestAddress -- 16] [15-- Source2  -- 8] [7-- Source1 -- 0] 
//
//  Clock : 4
//  Single Cycle Implementation
// 
//***************************************************


module main;

	reg CLK, RESET;

	// wired connections
	wire [7:0] outOne, outOneComp, outTwo, outTwoComp;
	wire [7:0] dataOneReg, dataOneIm, dataOne, dataTwo;
	wire [7:0] aluResult;
	wire [7:0] PC;
	wire [2:0] aluSel;

	wire [31:0] ins;
	wire aSelect, bSelect, iSelect; 	// Mux selector pins | muxA, muxB and muxImd
	wire regWriteEn;
	wire [2:0] outOneAddr,outTwoAddr, inAddr;
	
	// Control Unit
	// aluSelect,dataOneIm, outOneAddr,outTwoAddr,inAddr, aSelect,bSelect,iSelect, PC, INS, CLK
	controlUnit myControl(aluSel,dataOneIm, outOneAddr,outTwoAddr,inAddr, aSelect,bSelect,iSelect,regWriteEn, PC, ins, CLK);

	// ALU(result, dataA, dataB, select)
	ALU myALU(aluResult, dataOne,dataTwo, aluSel);
	
	// mux8(OUT, A,B, S)
	mux8 muxA(dataOneReg,outOne,outOneComp, aSelect);
	mux8 muxB(dataTwo,outTwo,outTwoComp, bSelect);
	mux8 muxImd(dataOne,dataOneReg,dataOneIm, iSelect);
	
	// compTwo(OUT,IN)
	compTwo compA(outOneComp,outOne);
	compTwo compB(outTwoComp,outTwo);
	
	// regFile(regIn, regOutA, regOutB, regAddrIn, regAddrA, regAddrB, CLK, RESET, WRITE_EN)
	regFile myReg(aluResult, outOne, outTwo, inAddr, outOneAddr, outTwoAddr, CLK, RESET, regWriteEn);
	
	// instructions
	instReg myInst(ins, PC, CLK);
	
	 // Program Counter
	 programCounter myPC(PC, CLK, RESET);
	

	initial begin
		//outOneAddr <= 3'b000;
        //outTwoAddr <= 3'b001;
        //inAddr <= 3'b010;
		
		#0
		$dumpfile("myReg.vcd"); 
		
	    $dumpvars(0, myReg);
		$dumpvars(0, myALU);
		$dumpvars(0, muxA);
		$dumpvars(0, muxImd);
		$dumpvars(0, myControl);
		$dumpvars(0, myInst);
		
		RESET <= 1;
		
		#1
		RESET <= 0;
		
        #4
		CLK <= 0;   
        
		#50
		$finish;
		
	end
	
	always #2 CLK = ~CLK;
    
endmodule

module controlUnit(aluSelect,dataOneIm, outOneAddr,outTwoAddr,inAddr, aSelect,bSelect,iSelect,regWriteEn,PC, INS, CLK);
	
	// Note: Need to change Load Immediate option. 
	
	input [31:0] INS;      		 // instruction
	input [7:0] PC;      			// program counter
	input CLK;
	
	output [2:0] aluSelect;	// select ALU operation
	
	output [7:0] dataOneIm; 	// dataOne Immediate Value
	output [2:0] outOneAddr, outTwoAddr, inAddr;
	output aSelect, bSelect, iSelect, regWriteEn;

	reg [31:0] IR;					// instruction register
    reg [7:0] opcode;
	reg [7:0] EEPROM[7:0];
	
	 initial begin
			// aluSelect(3), dataOneIm(1),  aSelect(1), bSelect(1), iSelect(1)
			EEPROM[0] <=  8'b00000011;		// loadi
			EEPROM[1] <=  8'b00000001;		// mov
			EEPROM[2] <=  8'b00100001;		// add
			EEPROM[3] <=  8'b00100101;		// sub
			EEPROM[4] <=  8'b01000001;		// and
			EEPROM[5] <=  8'b01100001;		// or
			EEPROM[6] <=  8'b00000001;		// load
			EEPROM[7] <=  8'b00000000;		// store
 	end
	
	assign aluSelect = EEPROM[opcode][7:5];
	//assign dataOneIm = EEPROM[opcode][4];
	assign aSelect = EEPROM[opcode][3];
	assign bSelect = EEPROM[opcode][2];
	assign iSelect = EEPROM[opcode][1];
	assign regWriteEn = EEPROM[opcode][0];
	
	assign inAddr = IR[18:16];
	assign outTwoAddr = IR[10:8];
	assign outOneAddr = IR[2:0];
	assign dataOneIm = IR[2:0];

   always @(posedge CLK) begin
		IR <= INS;
		opcode <= INS[31:24];	
		//$display("PC: %b | Inst: %b \n", PC, INS[31:24]); 
		$display("PC: %b | Instruction: %b | %b | %b | %b \n", PC, IR[31:24], IR[23:16], IR[15:8], IR[7:0] ); // IR[18:16], IR[10:8], IR[2:0] 
	end 
	
	//always @(posedge RESET) begin
	    //$display("Processor Reset");
		//assign PC = 8'b00000000;
	//end
	
endmodule

module instReg(inst, instAddr, CLK);
    // 32-bit instruction register of 256 instructions
	
    input CLK;						// Control inputs
    input [7:0] instAddr;		// Instruction address
    output [31:0] inst;			// Instruction values
    
    reg [255:0] memory [31:0];	// Storage
	reg [31:0] currInst;
	
	integer i;		// For reset option,  will be implement in future

    initial begin
        // Load the data into registers at the begin.
		//	Format	:			  |  OPCODE |  Dest  |  Source2  |  Source1  |
		
		//add r8, r0, r1
		/*
		memory[0] [31:24]<= 8'b00000010;		// Op-code
		memory[0] [23:16]<= 8'b00001000;		// Destination
		memory[0] [15:8]<=  8'b00000000;		// Source2 
		memory[0] [7:0]<=  8'b00000001;			// Source1
		*/
		
		//loadi r0, #3
		memory[0] [31:24]<= 8'b00000000;		// Op-code
		memory[0] [23:16]<= 8'b00000000;		// Destination
		memory[0] [15:8]<=  8'b00000000;		// Source2 
		memory[0] [7:0]<=  8'b00000011;		// Source1

		// loadi r1, #5
		memory[1] [31:24]<= 8'b00000000;		// Op-code
		memory[1] [23:16]<= 8'b00000001;		// Destination
		memory[1] [15:8]<=  8'b00000000;		// Source2 
		memory[1] [7:0]<=  8'b00000101;		// Source1
	

		// loadi r3, #2
		memory[2] [31:24]<= 8'b00000000;		// Op-code
		memory[2] [23:16]<= 8'b00000011;		// Destination
		memory[2] [15:8]<=  8'b00000000;		// Source2 
		memory[2] [7:0]<=  8'b00000010;		// Source1
	
		//add r4, r0, r1
		memory[3] [31:24]<= 8'b00000010;		// Op-code
		memory[3] [23:16]<= 8'b00000100;		// Destination
		memory[3] [15:8]<=  8'b00000001;		// Source2 
		memory[3] [7:0]<=  8'b00000001;		// Source1
		
		//sub r5, r0, r1
		memory[4] [31:24]<= 8'b00000011;		// Op-code
		memory[4] [23:16]<= 8'b00000101;		// Destination
		memory[4] [15:8]<=  8'b00000000;		// Source2 
		memory[4] [7:0]<=  8'b00000001;		// Source1
		
		// and r6, r0, r1
		memory[5] [31:24]<= 8'b00000100;		// Op-code
		memory[5] [23:16]<= 8'b00000110;		// Destination
		memory[5] [15:8]<=  8'b00000000;		// Source2 
		memory[5] [7:0]<=  8'b00000001;		// Source1
		
		//or r7, r0, r1
		memory[6] [31:24]<= 8'b00000101;		// Op-code
		memory[6] [23:16]<= 8'b00000111;		// Destination
		memory[6] [15:8]<=  8'b00000000;		// Source2 
		memory[6] [7:0]<=  8'b00000001;		// Source1
		
		//mov r7, r2
		memory[7] [31:24]<= 8'b00000101;		// Op-code
		memory[7] [23:16]<= 8'b00000111;		// Destination
		memory[7] [15:8]<=  8'b00000000;		// Source2 
		memory[7] [7:0]<=  8'b00000010;		// Source1
		
		// load r6, r2
		memory[8] [31:24]<= 8'b00000101;		// Op-code
		memory[8] [23:16]<= 8'b00000111;		// Destination
		memory[8] [15:8]<=  8'b00000000;		// Source2 
		memory[8] [7:0]<=  8'b00000010;		// Source1
		
		// store r5, r2
		memory[9] [31:24]<= 8'b00000101;		// Op-code
		memory[9] [23:16]<= 8'b00000111;		// Destination
		memory[9] [15:8]<=  8'b00000000;		// Source2 
		memory[9] [7:0]<=  8'b00000010;		// Source1
		
		
		
		// Instruction 3
		//memory[3] <= 32'b00000100000000000000000000000000 ;
		
		
    end
	
    always @(posedge CLK) begin
		//---> IF:  Instruction Fetch (Stage 1)
        currInst <= memory[instAddr];
        //$display("PC: %b | Instruction: %b \n", instAddr, currInst); // IR[18:16], IR[10:8], IR[2:0] 
    end
	
    assign inst = currInst;
	
endmodule

module programCounter(OUT, CLK, RESET);
	// 8-bit program counter
	
	input CLK, RESET;
	output [7:0] OUT;      // PC value (output)
	
    reg [7:0] pc;
    
	// REM: use pos or neg edge
    always @(posedge CLK) begin
		pc <= (pc + 8'b00000001);
		//$display("PC = %b", pc); 
	end
	
	always @(posedge RESET) begin
		pc <= 8'b00000000;
		//$display("PC Reset"); 
	end
	
	assign OUT = pc;
	
endmodule

module ALU(Result,DATA1,DATA2,Select);
	input [7:0] DATA1;
	input [7:0] DATA2;
	input [2:0] Select;
	output [7:0] Result;
	reg [7:0] Result;

	always@(DATA1,DATA2,Select)
	begin
	case(Select)
		3'b000:  Result = DATA1;
		3'b001:  Result = DATA1+DATA2;
		3'b010:  Result = DATA1&DATA2;
		3'b011:  Result = DATA1|DATA2;

	endcase
	end
endmodule

/*module ALU(result, dataA, dataB, select);

    input [7:0] dataA,dataB;    // Input data paths
	input [2:0] select;         // ALU operation    0:Forward, 1:Add, 2:AND, 3:OR, 4-7:N/A
	output [7:0] result;
	
    reg [7:0] res;

    always @(select) begin
		case(select)
            0: res = dataA; 		    // FORWARD
            1: res = dataA + dataB;	// ADD
            2: res= dataA & dataB;	// AND 
            3: res = dataA | dataB;	// OR
            default: res = 0;
        endcase
		
		if(select == 0)begin
			res <= dataA; 
			$display(">>> %b - %b %b" , select, dataA,  res);
		end
	end
    
	assign result = res;
	
endmodule
*/

module mux8(OUT, A,B, S);
    input [7:0] A,B;        		// A and B input data paths
    input S;         					// selector input
    output [7:0] OUT;       	// Output data path
    reg [7:0] R;

    always @(*) begin       // if(S==0){R=A} else{R=B}
        R <= (S == 0) ? A : B;
    end
    assign OUT = R;
endmodule

module compTwo(OUT,IN);  
    input [7:0] IN;     				// Input Signal      
    output [7:0] OUT;   		// 2's complemented output signal
    reg [7:0] R;

    always @(IN) begin
        R <= -1*IN;
    end
    assign OUT = R;
endmodule

module regFile(regIn, regOutA, regOutB, regAddrIn, regAddrA, regAddrB, CLK, RESET, WRITE_EN);
    input CLK, RESET, WRITE_EN;			// Control inputs
    input [2:0] regAddrIn;				// Input address
    input [2:0] regAddrA, regAddrB;		// Output addresses
    input [7:0] regIn;					// Input value 
    output [7:0] regOutA, regOutB;		// Output value
    reg [7:0] A, B;
    reg [7:0] registers[7:0];

	integer i;
	wire writeEnable;			// Only writes to the register when WRITE_EN=1
	and(writeEnable, CLK, WRITE_EN);

	// -- Inputs -----------------------------------------------
    always @(posedge CLK) begin
		//---> DR:  Data Write (Stage 5)
        if (writeEnable==1) begin 
			registers[regAddrIn] <= regIn;
			$display("RegWrite the value %d into r%d", regIn, regAddrIn); 
		end
    end

	// -- Outputs ---------------------------------------------- 
    always @(negedge CLK) begin
		//---> ID: Instruction Decode (Stage 2)
        A <= registers[regAddrA];
        B <= registers[regAddrB];
    end

    assign regOutA = A;
    assign regOutB = B;
    
	// -- Reset -----------------------------------------------
    always @(posedge RESET) begin
        for(i=0;i<8;i=i+1) begin
    	    registers[i] <= 8'b00000000;
    	end
    	//$display("Register Reset"); 
	end
	
	 initial begin	
		// Set all values to zero at the beginning
	    for(i=0;i<8;i=i+1) begin
    	    registers[i] <= 8'b00000000;
    	end
	 end
	 
endmodule

