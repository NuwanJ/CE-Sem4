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
	wire [7:0] dataOneReg, dataOneIm, dataOne, dataTwo, dataWriteToReg;
	wire [7:0] aluResult;
	wire [7:0] PC;
	wire [2:0] aluSel;

	wire [31:0] ins;
	wire aSelect, bSelect, iSelect, wSelect; 	// Mux selector pins | muxA, muxB and muxImd
	wire regWriteEn;
	wire busy;	
	
	wire [2:0] outOneAddr,outTwoAddr, inAddr;

	wire dataRead, dataWrite;		// read / write control signals
	wire [7:0] wAddr,wData, rData;					// Address and the value should written into data memory
		
	wire addr [7:0];
	wire readData [7:0];
	
	wire BUSY;

	// Control Unit
	controlUnit myControl(wSelect, dataRead, dataWrite, wAddr, aluSel,dataOneIm, outOneAddr,outTwoAddr,inAddr, aSelect,bSelect,iSelect,regWriteEn, PC, ins,CLK, BUSY);

	// ALU(result, dataA, dataB, select)
	ALU myALU(aluResult, dataOne,dataTwo, aluSel);
	
	// mux8(OUT, A,B, S)
	mux8 muxA(dataOneReg,outOne,outOneComp, aSelect);
	mux8 muxB(dataTwo,outTwo,outTwoComp, bSelect);
	mux8 muxImd(dataOne,dataOneReg,dataOneIm, iSelect);
	
	mux8 muxWriteToReg(dataWriteToReg, aluResult, rData, wSelect);
	
	// compTwo(OUT,IN)
	compTwo compA(outOneComp,outOne);
	compTwo compB(outTwoComp,outTwo);
	
	// regFile(regIn, regOutA, regOutB, regAddrIn, regAddrA, regAddrB, CLK, RESET, WRITE_EN)
	regFile myReg(dataWriteToReg, outOne, outTwo, inAddr, outOneAddr, outTwoAddr, CLK, RESET, regWriteEn);
	
	// instructions
	instReg myInst(ins, PC, CLK);
	
	 // Program Counter
	 programCounter myPC(PC, CLK, RESET, BUSY);
	
     // Data Memory 
	data_mem myMem(CLK,RESET,dataRead,dataWrite,wAddr,aluResult,rData,BUSY);

	initial begin

		#0
		$dumpfile("myProcessor.vcd"); 
		
	    $dumpvars(0, myReg);
		$dumpvars(0, myALU);
		$dumpvars(0, muxA);
		$dumpvars(0, muxImd);
		$dumpvars(0, myControl);
		$dumpvars(0, myInst);
		$dumpvars(0, muxWriteToReg);
		$dumpvars(0, myMem);
		
		RESET <= 1;
		
		#1
		RESET <= 0;
		
        #4
		CLK <= 0;   
        
		#200
		$finish;
		
	end
	
	always #2 CLK = ~CLK;
    
endmodule

//  controlUnit myControl(dataRead, dataWrite, wAddr, aluSel,dataOneIm, outOneAddr,outTwoAddr,inAddr, aSelect,bSelect,iSelect,regWriteEn, PC, ins,,CLK, WAIT);
module controlUnit(wSelect, dataRead, dataWrite, wAddr,aluSelect,dataOneIm, outOneAddr,outTwoAddr,inAddr, aSelect,bSelect,iSelect,regWriteEn,PC, INS,CLK, WAIT);
	
	input [31:0] INS;      		 // instruction
	input [7:0] PC;      			// program counter
	input CLK, WAIT;
	
	output [2:0] aluSelect;	// select ALU operation
	
	output [7:0] dataOneIm; 	// dataOne Immediate Value
	output [2:0] outOneAddr, outTwoAddr, inAddr;
	output aSelect, bSelect, iSelect, regWriteEn, wSelect;
	
	output dataRead, dataWrite;
	output [7:0] wAddr;
	
	reg [31:0] IR;					// instruction register
    reg [7:0] opcode;
	reg [7:0] EEPROM[11:0];
	
	 initial begin
									// wSelect _ dataRead, dataWrite _ aluSelect(3) _ dataOneIm(1),  aSelect(1), bSelect(1), iSelect(1) 
			EEPROM[0] <=  11'b00000000011;		// loadi
			EEPROM[1] <=  11'b00000000001;		// mov
			EEPROM[2] <=  11'b00000100001;		// add
			EEPROM[3] <=  11'b00000100101;		// sub
			EEPROM[4] <=  11'b00001000001;		// and
			EEPROM[5] <=  11'b00001100001;		// or
			EEPROM[6] <=  11'b11000000001;		// load
			EEPROM[7] <=  11'b00100000000;		// store
 	end
	
	assign dataWrite = (opcode == 7 ) ? 1'b1 : 1'b0;
	assign dataRead = (opcode == 6 ) ? 1'b1 : 1'b0;
	assign wSelect = (opcode == 6 ) ? 1'b1 : 1'b0;
	
	assign wAddr = IR[23:16];
	
	assign aluSelect = EEPROM[opcode][7:5];
	//assign dataOneIm = EEPROM[opcode][4];
	assign aSelect = EEPROM[opcode][3];
	assign bSelect = EEPROM[opcode][2];
	assign iSelect = EEPROM[opcode][1];
	assign regWriteEn = EEPROM[opcode][0];
	
	assign inAddr = IR[18:16];
	assign outTwoAddr = IR[10:8];
	assign outOneAddr = IR[2:0];
	assign dataOneIm = IR[7:0];

   always @(posedge CLK) begin
		IR <= INS;
		opcode <= INS[31:24];	
		$display("PC: %d | Instruction: %h | %h | %h | %h \n", PC, IR[31:24], IR[23:16], IR[15:8], IR[7:0] ); // IR[18:16], IR[10:8], IR[2:0] 
	end 

endmodule
module data_mem(clk,rst,read,write,address,write_data,read_data,busy_wait);

	input           clk,rst,read,write;
	input[7:0]      address,write_data;
	output[7:0]     read_data;

	output	busy_wait;

	reg[7:0]     read_data;
	reg busy;
	integer  i;

	reg [7:0] memory_array [255:0];

	always @(posedge rst) begin
		if (rst)
		begin
			busy<= 0;
			for (i=0;i<256; i=i+1)
				memory_array[i] <= 0;
		end
	end

	
	always @(negedge clk) begin
			if(!write && read && !busy) begin
				busy <= 1;
				#400;		// 100 x 4cc
				$display ("Read %b from  %b", read_data, address);
				read_data = memory_array[address];
				busy <= 0;
			end
			
			if (write && !read && !busy) begin
				busy <= 1;
				#400;		// 100 x 4cc
				$display ("Written %b into  %b", write_data, address);
				memory_array[address] = write_data;
				busy <= 0;
			end
	end 
	
	assign busy_wait = busy;
	
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
		
		//loadi r0,x,#32;
		memory[0] [31:24]<= 8'b00000000;		// Op-code
		memory[0] [23:16]<= 8'b00000000;		// Destination
		memory[0] [15:8]<=  8'b00000000;		// Source2 
		memory[0] [7:0]<=  	8'b00100000;		// Source1

		// loadi r1,x,#8;
		memory[1] [31:24]<= 8'b00000000;		// Op-code
		memory[1] [23:16]<= 8'b00000001;		// Destination
		memory[1] [15:8]<=  8'b00000000;		// Source2 
		memory[1] [7:0]<=   8'b00001000;		// Source1
	

		// str #2,x,r0;
		memory[2] [31:24]<= 8'b00000111;		// Op-code
		memory[2] [23:16]<= 8'b00000010;		// Destination
		memory[2] [15:8]<=  8'b00000000;		// Source2 
		memory[2] [7:0]<=   8'b00000000;		// Source1
	
		//str #6,x,r1;
		memory[3] [31:24]<= 8'b00000111;		// Op-code
		memory[3] [23:16]<= 8'b00000110;		// Destination
		memory[3] [15:8]<=  8'b00000000;		// Source2 
		memory[3] [7:0]<=   8'b00000001;		// Source1
		
		//load r2,x,#2;
		memory[4] [31:24]<= 8'b00000110;		// Op-code
		memory[4] [23:16]<= 8'b00000010;		// Destination
		memory[4] [15:8]<=  8'b00000000;		// Source2 
		memory[4] [7:0]<=   8'b00000010;		// Source1
		
		//load r3,x,#6;
		memory[5] [31:24]<= 8'b00000110;		// Op-code
		memory[5] [23:16]<= 8'b00000011;		// Destination
		memory[5] [15:8]<=  8'b00000000;		// Source2 
		memory[5] [7:0]<=  8'b00000110;		// Source1
		
		//load r5,x,#4;
		memory[5] [31:24]<= 8'b00000110;		// Op-code
		memory[5] [23:16]<= 8'b00000101;		// Destination
		memory[5] [15:8]<=  8'b00000000;		// Source2 
		memory[5] [7:0]<=  	8'b00000100;		// Source1		
	
		
    end
	
    always @(posedge CLK) begin
		//---> IF:  Instruction Fetch (Stage 1)
        currInst <= memory[instAddr];
        //$display("PC: %b | Instruction: %b \n", instAddr, currInst); // IR[18:16], IR[10:8], IR[2:0] 
    end
	
    assign inst = currInst;
	
endmodule

module programCounter(OUT, CLK, RESET, BUSY);
	// 8-bit program counter
	
	input CLK, RESET, BUSY;
	output [7:0] OUT;      // PC value (output)
	
    reg [7:0] pc;
    
	// REM: use pos or neg edge
    
		always @(posedge CLK) begin
			if (BUSY==0) begin
				pc <= (pc + 8'b00000001);
				//$display("PC = %b", pc); 
			end
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
