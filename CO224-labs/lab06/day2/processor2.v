module main;
	reg RESET;
	wire [31:0] instruction;
	reg CLK;
	wire BUSY;
	
	wire aSelect, bSelect, iSelect, regWriteEn;
	wire [2:0] INaddr, OUT1addr, OUT2addr;
	wire [2:0] aluSelect;
	wire [7:0] ImmediateValue;
	
	wire [7:0] aluResult, out1, out2, out1Invert, out2Invert, muxAOut, muxBOut, muxIOut;
	
	wire [7:0] PC;
	
	wire readEn,writeEn;
	wire [7:0] dataAddress,dataOut;
	wire [7:0] regIn;
	
	//controlUnit(CLK, BUSY, INST, INaddr, OUT1addr, OUT2addr, ImmediateValue, aSelect, bSelect, iSelect, aluSelect, regWriteEn);
	controlUnit myControl(CLK,BUSY, instruction, INaddr, OUT1addr, OUT2addr, ImmediateValue, aSelect, bSelect, iSelect, aluSelect, regWriteEn, PC, writeEn,readEn,dataAddress,wSelect);
	
	// ALU(aluResult, data1,data2,aluSelect);
	ALU myALU(aluResult, muxIOut, muxBOut, aluSelect);
	
	// regFile(IN,OUT1,OUT2,INaddr,OUT1addr,OUT2addr,CLK,RESET,WRITE_EN);
	regFile myRegFile(regIn, out1, out2, INaddr, OUT1addr, OUT2addr, CLK, RESET, regWriteEn);
	
	// 2's complement units (Out, In)
	compTwo compA(out1Invert, out1);
	compTwo compB(out2Invert, out2);
	
	// mux for data paths (Out, 0-input, 1-input)
	mux muxA(muxAOut, out1 ,out1Invert, aSelect);
	mux muxB(muxBOut, out2, out2Invert, bSelect);
	mux muxI(muxIOut, muxAOut, ImmediateValue, iSelect);
	
	// instruction register (inst, instAddr, CLK) 
	instReg myInst(instruction, PC, CLK);
	
	// program counter (PC, CLK, RESET, BUSY)
	programCounter myPC(PC, CLK, RESET, BUSY);
	
	// data memory (CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY)
	data_mem myMem(CLK, RESET, readEn,writeEn ,dataAddress , dataOut, aluResult, BUSY);
	
	// new mux for  ( aluResult | dataOut )---> regIn (control signal = wSelect)
	mux muxW(regIn,aluResult,dataOut,wSelect);
	
	initial begin

		#0
		$dumpfile("myProcessor.vcd"); 
		$dumpvars(0, myControl);
		$dumpvars(0, myALU);
		$dumpvars(0, myRegFile);
		$dumpvars(0, myPC);
		$dumpvars(0, myMem);
			

		$dumpvars(0, compA);
		$dumpvars(0, muxA);
		$dumpvars(0, compB);
		$dumpvars(0, muxB);
		$dumpvars(0, muxI);
				
		RESET <= 0;
		CLK <= 0;
		//BUSY <= 0;
		
		#300
		$finish;
		
	end
	
	always begin
		#5 CLK = ~CLK;
    end
	
	//always begin
		//#5 
		//$display("PC = %d", PC);
    //end
		
endmodule

//not completed
module controlUnit(CLK, BUSY, INST, INaddr, OUT1addr, OUT2addr, ImmediateValue, aSelect, bSelect, iSelect, aluSelect, regWriteEn, PC, writeEn,readEn,dataAddress,wSelect);
	 output [7:0] dataAddress;
	 output writeEn, readEn, wSelect;
	
	input CLK,BUSY;
	input [31:0] INST;
	
	input [7:0] PC;
	
	output [2:0] OUT1addr, OUT2addr, INaddr;
	output [7:0] ImmediateValue;
	
	output aSelect, bSelect, iSelect, regWriteEn;
	output [2:0] aluSelect;

	reg [31:0] IR;					// instruction register
	
	reg [7:0] opcode;
	reg [7:0] destination;
	reg [7:0] source2;
	reg [7:0] source1;
	reg [9:0] currentControlCode;
	reg [9:0] EEPROM[7:0];
		
	initial begin
										// regWrite_aluSelect(3)_dataOneIm(1)_aSelect(1)_bSelect(1)_iSelect(1)_NC 
		EEPROM[0] <=  9'b1_000_1_0_0_1_0;		// loadi
		EEPROM[1] <=  9'b1_000_0_0_0_0_0;		// mov
		EEPROM[2] <=  9'b1_001_0_0_0_0_0;		// add
		EEPROM[3] <=  9'b1_001_0_0_1_0_0;		// sub
		EEPROM[4] <=  9'b1_010_0_0_0_0_0;		// and
		EEPROM[5] <=  9'b1_011_0_0_0_0_0;		// or
		EEPROM[6] <=  9'b1_000_0_0_0_0_0;		// load
		EEPROM[7] <=  9'b0_000_0_0_0_0_0;		// store
	end

	assign readEn=(opcode==6)? 1'b1 : 1'b0;
	assign writeEn=(opcode==7)? 1'b1 : 1'b0;
	assign wSelect=(opcode==6)? 1'b1 : 1'b0;
	
	// ALU operation selector
	assign aluSelect = currentControlCode[7:5];
	
	// Address selectors for register
	assign OUT1addr = source1[2:0];
	assign OUT2addr = source2[2:0];
	assign INaddr = destination[2:0];
	
	assign regWriteEn = currentControlCode[8];
	
	assign ImmediateValue = source1;
	assign dataAddress = (opcode==6) ? source1 : destination;
	
	// Control Signals for MUX modules
	assign aSelect = currentControlCode[3];
	assign bSelect = currentControlCode[2];
	assign iSelect = currentControlCode[1];
	
	 always @(PC) begin
		IR <= INST;
		opcode <= IR[31:24];	
		destination <= IR[23:16];	
		source2 <= IR[15:8];	
		source1 <= IR[7:0];	
		currentControlCode = EEPROM[opcode][11:0];
		$display("\nPC: %d | Inst: %d | %d | %d | %d", PC, opcode, destination, source2, source1);
	end 
	
endmodule

	// ALU is tested and working now
module ALU(aluResult, data1,data2,aluSelect);

	input [7:0] data1,data2;
	input [2:0] aluSelect;
	output [7:0] aluResult;
	
    reg [7:0] aluResult;
   
	always @ (data1 or data2 or aluSelect) begin
    	case (aluSelect)
    		3'b000: aluResult = data1;
    		3'b001: aluResult = data1+data2;
    		3'b010: aluResult = data1&data2;
    		3'b011: aluResult = data1|data2;
    	endcase
	end
	
endmodule

// Mux is tested and working
module mux(OUT,A,B,S);

	input [7:0] A,B;
	input S;
	output [7:0] OUT;
		
	assign OUT=(S==0)? A:B;

endmodule

// Comp is tested and working
module compTwo(OUT,IN);
	input [7:0] IN;
	output [7:0] OUT;
	assign OUT=(~IN+1);
endmodule
	
// register is tested and working
module regFile(IN,OUT1,OUT2,INaddr,OUT1addr,OUT2addr,CLK,RESET,WRITE_EN);

	input CLK,RESET,WRITE_EN;
	input [2:0] INaddr,OUT1addr,OUT2addr;
	input [7:0] IN;
	output [7:0] OUT1,OUT2;

	reg [7:0] registers[7:0];
    reg [7:0] OUT1,OUT2;
    
	integer i;
	
	always @(posedge CLK) begin
		OUT1 <= registers[OUT1addr];
		OUT2 <= registers[OUT2addr];
		$display("\tReg-> %d [%d] and %d [%d]", OUT1, OUT1addr, OUT2, OUT2addr); 
		
		//for (i=0;i<8; i=i+1)
		//	$display ("register[ %d ] = %b", i, registers[i]);
				
	end
	
	always @(negedge CLK) begin
		if(WRITE_EN)  begin
			registers[INaddr]<=IN;
			$display("\n\t-> Reg the value %d into r%d", IN, INaddr); 
			
			//for (i=0;i<8; i=i+1)
			//		$display ("register[ %d ] = %b", i, registers[i]);
					
		end
	end
	
	// Reset the register values
	always @(posedge RESET) begin   
        $display("Register reset"); 
        for(i=0;i<8;i=i+1) begin
    	    registers[i] <= 8'b00000000;
    	end
	end

	// Begin with 0 values
	initial begin
        for(i=0;i<8;i=i+1) begin
    	    registers[i] <= 8'b00000000;
    	end
	end
	
endmodule	

// instruction register is working on every falling edge
module instReg(instCurrent, instAddr, CLK);

    input CLK;
    input [7:0] instAddr;
    output [31:0] instCurrent;
    
    reg [255:0] memory [31:0];
	reg [31:0] instCurrent;
	
	integer i;		// For reset option,  will be implement in future

    initial begin
		//	Format	:			  |  OPCODE |  Dest  |  Source2  |  Source1  |
		
		// empty
		memory[0] <= 32'b00000000_00000000_xxxxxxxx_00000000;
		
		// loadi r0,x,#32;
		//memory[1] <= 32'b00000000_00000000_xxxxxxxx_00001000;
		
		// loadi r1,x,#8;
		//memory[2] <= 32'b00000000_00000001_xxxxxxxx_00100000;
		
		// loadi r2,x,#12;
		//memory[3] <= 32'b00000000_00000010_xxxxxxxx_00001100;
		
		// add r4, r0, r1
		//memory[4] <= 32'b00000010_00000100_00000000_00000001;
		
		// sub r5, r0, r1
		//memory[5] <= 32'b00000011_00000101_00000000_00000001;
		
		//mov r0, xx, r4
		//memory[6] <= 32'b00000001_00000000_xxxxxxxx_00000100;
		
		// or r6, r0, r1
		//memory[7] <= 32'b00000101_00000110_00000000_00000001;
		
		// and r7, r0, r1
		//memory[8] <= 32'b00000100_00000111_00000000_00000001;
		
	
		//
		//loadi r0,x,#32;
		memory[1] <= 32'b00000000_00000000_00000000_00100000;
		
		// loadi r1,x,#8;
		memory[2] <= 32'b00000000_00000001_00000000_00001000;
		
		// str #2,x,r0;
		memory[3] <= 32'b00000111_00000010_00000000_00000000;
		
		//str #6,x,r1;	
		memory[4] <= 32'b00000111_00000110_00000000_00000001;	

		//load r2,x,#2;
		memory[5]  <= 32'b00000110_00000010_00000000_00000010;
		
		//load r3,x,#6;
		memory[6]  <= 32'b00000110_00000011_00000000_00000110;

		//load r5,x,#4;
		memory[7]  <= 32'b00000110_00000101_00000000_00000100;	
	
    end

	always @(posedge CLK) begin
		instCurrent <= memory[instAddr];
	end
	
endmodule

// Program Counter is working
module programCounter(PC, CLK, RESET, BUSY);
	
	input CLK, RESET, BUSY;
	output [7:0] PC;    
	
    reg [7:0] PC;
    
    always @(posedge CLK) begin
		// PC increases on negative edge
		if (BUSY==0) begin
			PC <= (PC + 8'b00000001);
		end
	end
	
	always @(posedge RESET) begin
		// reseting the PC value to zero
		PC <= 0;
	end
	
	initial begin
		// inisital value for PC is zero 
	    PC <= 0;
	end
	
endmodule




