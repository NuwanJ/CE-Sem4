
module main;

    reg [7:0] A,B;
    reg [2:0] sel;
	output [7:0] R;
	//reg [4:0] i;
	ALU myALU(R, A,B, sel);
	
	initial begin
		A <= 8'b00000111;
		B <= 8'b00000011;
		
		sel <= 5; //3'b011;
		// 0:FORWARD 1:ADD 2:AND 3:OR
	
		#1 $display("A= %d B =%d ===( %d )===> Output =%d \n", A, B, sel, R);
	end
	
endmodule

module ALU(result, A, B, selection);

    input [7:0] A,B;
	input [2:0] selection;
	output [7:0] result ;
	
    reg [7:0] R;

    always @(selection)
	begin
        case(selection)
        0: // FORWARD
           R = A; 
    
        1: // ADD
           R = A + B ; 
        
        2: // AND 
           R = A & B;
        
        3: // OR
           R = A | B;
        
        default: 
            R = 0;
        endcase
    end

	assign result = R;
	
endmodule
